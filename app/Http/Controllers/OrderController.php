<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Obat;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\BiteshipService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderController extends Controller
{
    private BiteshipService $biteshipService;

    public function __construct(BiteshipService $biteshipService)
    {
        $this->middleware('auth');
        $this->biteshipService = $biteshipService;
    }

    /**
     * Calculate shipping cost
     */
    public function calculateShipping(Request $request): JsonResponse
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'quantity' => 'required|integer|min:1',
            'postal_code' => 'required|string|size:5',
        ]);

        try {
            $obat = Obat::findOrFail($request->obat_id);
            $quantity = $request->quantity;
            
            // Calculate total weight
            $totalWeight = ($obat->weight ?? 100) * $quantity; // Default 100g if weight not set
            
            // Prepare items for Biteship
            $items = BiteshipService::formatItems([
                [
                    'name' => $obat->nama,
                    'description' => $obat->deskripsi ?? $obat->nama,
                    'value' => $obat->harga,
                    'weight' => $obat->weight ?? 100,
                    'quantity' => $quantity,
                ]
            ]);

            // Get shipping rates
            $shippingResponse = $this->biteshipService->getShippingRates([
                'destination_postal_code' => $request->postal_code,
                'items' => $items,
            ]);

            if (!$shippingResponse['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $shippingResponse['message'] ?? 'Gagal menghitung ongkir',
                ], 400);
            }

            // Parse shipping rates
            $rates = BiteshipService::parseShippingRates($shippingResponse['data']);

            if (empty($rates)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada layanan pengiriman yang tersedia untuk kode pos ini',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'rates' => $rates,
                    'product' => [
                        'name' => $obat->nama,
                        'price' => $obat->harga,
                        'weight' => $obat->weight ?? 100,
                        'subtotal' => $obat->harga * $quantity,
                    ]
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Calculate Shipping Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
            ], 500);
        }
    }

    /**
     * Get area suggestions by postal code
     */
    public function getAreaByPostalCode(Request $request): JsonResponse
    {
        $request->validate([
            'postal_code' => 'required|string|size:5',
        ]);

        try {
            $result = $this->biteshipService->getAreaByPostalCode($request->postal_code);
            
            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode pos tidak ditemukan',
                ], 404);
            }

            $areas = $result['data']['areas'] ?? [];
            
            if (empty($areas)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada area yang ditemukan untuk kode pos ini',
                ], 404);
            }

            // Get the first area match
            $area = $areas[0];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'city' => $area['administrative_division_level_2_name'] ?? '',
                    'district' => $area['administrative_division_level_3_name'] ?? '',
                    'postal_code' => $area['postal_code'] ?? $request->postal_code,
                    'province' => $area['administrative_division_level_1_name'] ?? '',
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Get Area Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan informasi area',
            ], 500);
        }
    }

    /**
     * Process purchase
     */
    public function purchase(PurchaseRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->getProcessedData();
            $obat = Obat::findOrFail($data['obat_id']);
            
            // Check stock availability
            if ($obat->stock < $data['quantity']) {
                return back()->withErrors(['quantity' => 'Stok tidak mencukupi. Stok tersedia: ' . $obat->stock]);
            }
            
            // Calculate totals
            $subtotal = $obat->harga * $data['quantity'];
            $shippingCost = $data['shipping_cost'];
            $total = $subtotal + $shippingCost;
            
            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],
                'shipping_address' => $data['shipping_address'],
                'city' => $data['city'],
                'district' => $data['district'],
                'postal_code' => $data['postal_code'],
                'courier_name' => $data['courier_name'],
                'courier_service' => $data['courier_service'],
                'courier_details' => $data['courier_details'],
                'notes' => $data['notes'] ?? null,
            ]);
            
            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'obat_id' => $obat->id,
                'product_name' => $obat->nama,
                'price' => $obat->harga,
                'quantity' => $data['quantity'],
                'weight' => $obat->weight ?? 100,
                'subtotal' => $subtotal,
            ]);
            
            // Update stock
            $obat->decrement('stock', $data['quantity']);
            
            // Create delivery if needed (optional)
            $this->createDeliveryOrder($order, $data);
            
            DB::commit();
            
            return redirect()->route('orders.success', $order->id)
                ->with('success', 'Pesanan berhasil dibuat! Nomor pesanan: ' . $order->order_number);
            
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Purchase Error: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Create delivery order through Biteship (optional)
     */
    private function createDeliveryOrder(Order $order, array $data): void
    {
        try {
            $deliveryData = [
                'shipper_contact_name' => config('app.pharmacy_name', 'Apotek Gampil'),
                'shipper_contact_phone' => config('app.pharmacy_phone'),
                'shipper_contact_email' => config('app.pharmacy_email'),
                'shipper_organization' => config('app.pharmacy_name', 'Apotek Gampil'),
                'origin_contact_name' => config('app.pharmacy_contact_name'),
                'origin_contact_phone' => config('app.pharmacy_phone'),
                'origin_address' => config('app.pharmacy_address'),
                'origin_postal_code' => config('app.pharmacy_postal_code'),
                'destination_contact_name' => $data['recipient_name'],
                'destination_contact_phone' => $data['recipient_phone'],
                'destination_address' => $data['shipping_address'],
                'destination_postal_code' => $data['postal_code'],
                'courier_company' => $data['courier_name'],
                'courier_type' => $data['courier_service'],
                'items' => [
                    [
                        'name' => $order->items->first()->product_name,
                        'description' => 'Obat-obatan',
                        'value' => $order->subtotal,
                        'weight' => $order->items->sum(function($item) {
                            return $item->weight * $item->quantity;
                        }),
                        'quantity' => $order->items->sum('quantity'),
                    ]
                ],
                'reference_id' => $order->order_number,
            ];

            $response = $this->biteshipService->createDeliveryOrder($deliveryData);
            
            if ($response['success']) {
                $order->update([
                    'tracking_id' => $response['data']['id'] ?? null,
                    'waybill_id' => $response['data']['waybill_id'] ?? null,
                ]);
                
                Log::info('Delivery order created successfully', [
                    'order_id' => $order->id,
                    'tracking_id' => $response['data']['id'] ?? null
                ]);
            }
        } catch (Exception $e) {
            Log::error('Failed to create delivery order: ' . $e->getMessage(), [
                'order_id' => $order->id
            ]);
            // Don't fail the main order process if delivery creation fails
        }
    }

    /**
     * Track order shipment
     */
    public function trackShipment(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $order = Order::where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if (!$order->tracking_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor tracking belum tersedia',
                ], 400);
            }

            $trackingResponse = $this->biteshipService->trackOrder($order->tracking_id);

            if (!$trackingResponse['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal melacak pesanan',
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $trackingResponse['data']
            ]);

        } catch (Exception $e) {
            Log::error('Track Shipment Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem',
            ], 500);
        }
    }

    /**
     * Show success page
     */
    public function success($orderId)
    {
        $order = Order::with('items.obat')
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        return view('orders.success', compact('order'));
    }

    /**
     * Show user orders
     */
    public function index()
    {
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.index', compact('orders'));
    }

    /**
     * Show order details
     */
    public function show($id)
    {
        $order = Order::with('items.obat')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
            
        return view('orders.show', compact('order'));
    }

    /**
     * Cancel order (if still pending)
     */
    public function cancel($id)
    {
        try {
            DB::beginTransaction();
            
            $order = Order::with('items')
                ->where('id', $id)
                ->where('user_id', auth()->id())
                ->where('status', 'pending')
                ->firstOrFail();

            // Restore stock
            foreach ($order->items as $item) {
                $obat = Obat::find($item->obat_id);
                if ($obat) {
                    $obat->increment('stock', $item->quantity);
                }
            }

            // Update order status
            $order->update(['status' => 'cancelled']);
            
            // Cancel delivery order if exists
            if ($order->tracking_id) {
                $this->biteshipService->cancelDeliveryOrder($order->tracking_id);
            }

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', 'Pesanan berhasil dibatalkan');

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Cancel Order Error: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Gagal membatalkan pesanan']);
        }
    }
}