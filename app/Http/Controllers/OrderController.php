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
    public function purchase(PurchaseRequest $request)
{
    Log::info('Masuk ke purchase function');
    try {
        DB::beginTransaction();

        $obat = Obat::findOrFail($request->obat_id);

        // Check stock availability
        if ($obat->stock < $request->quantity) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi.']);
        }

        // Prepare shipping data based on method
        $shippingData = $this->prepareShippingData($request);

        // Hitung subtotal dan total amount
        $subtotal = $obat->harga * $request->quantity;
        $total = $subtotal + ($shippingData['shipping_cost'] ?? 0);

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => $this->generateOrderNumber(),
            'subtotal' => $subtotal,
            'total' => $total,
            'shipping_method' => $request->shipping_method,
            'shipping_cost' => $shippingData['shipping_cost'] ?? 0,
            'recipient_name' => $shippingData['recipient_name'],
            'recipient_phone' => $shippingData['recipient_phone'],
            'shipping_address' => $shippingData['shipping_address'],
            'postal_code' => $shippingData['postal_code'] ?? null,
            'city' => $shippingData['city'] ?? null,
            'district' => $shippingData['district'] ?? null,
            'courier_name' => $shippingData['courier_name'] ?? null,
            'courier_service' => $shippingData['courier_service'] ?? null,
            'courier_details' => $shippingData['courier_details'] ?? null,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Create order item
        OrderItem::create([
            'order_id' => $order->id,
            'obat_id' => $obat->id,
            'quantity' => $request->quantity,
            'price' => $obat->harga,
            'subtotal' => $subtotal, // pakai nilai yang sudah dihitung
        ]);

        // Update stock
        $obat->decrement('stock', $request->quantity);

        DB::commit();

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat!');

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Purchase error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses pesanan.']);
    }
}


    private function prepareShippingData(PurchaseRequest $request): array
    {
        switch ($request->shipping_method) {
            case 'courier':
                return [
                    'recipient_name' => $request->recipient_name,
                    'recipient_phone' => $request->recipient_phone,
                    'shipping_address' => $request->shipping_address,
                    'postal_code' => $request->postal_code,
                    'city' => $request->city,
                    'district' => $request->district,
                    'courier_name' => $request->courier_name,
                    'courier_service' => $request->courier_service,
                    'courier_details' => $request->courier_details,
                    'shipping_cost' => $request->shipping_cost,
                ];

            case 'instant':
                return [
                    'recipient_name' => $request->instant_recipient_name,
                    'recipient_phone' => $request->instant_recipient_phone,
                    'shipping_address' => $request->instant_shipping_address,
                    'postal_code' => $request->instant_postal_code,
                    'city' => 'Bandung',
                    'district' => 'Bandung',
                    'courier_name' => 'Instant',
                    'courier_service' => 'Gojek/GrabExpress',
                    'courier_details' => null,
                    'shipping_cost' => 0,
                ];

            case 'pickup':
                return [
                    'recipient_name' => auth()->user()->name,
                    'recipient_phone' => auth()->user()->phone ?? '08123456789',
                    'shipping_address' => 'Pickup di Apotek',
                    'postal_code' => null,
                    'city' => null,
                    'district' => null,
                    'courier_name' => 'Pickup',
                    'courier_service' => 'Ambil di Apotek',
                    'courier_details' => null,
                    'shipping_cost' => 0,
                ];

            default:
                throw new Exception('Invalid shipping method');
        }
    }

    private function generateOrderNumber(): string
    {
        return 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    public function showPaymentForm(Request $request)
{
    $obat = Obat::findOrFail($request->obat_id);
    $quantity = $request->quantity ?? 1;

    return view('checkout.payment', compact('obat', 'quantity'));
}

public function processCheckout(Request $request)
{
    try {
        DB::beginTransaction();

        $obat = Obat::findOrFail($request->obat_id);

        if ($obat->stock < $request->quantity) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi.']);
        }

        // Hitung subtotal dan total
        $subtotal = $obat->harga * $request->quantity;
        $shippingCost = ($request->shipping_method === 'delivery') ? 15000 : 0;
        $total = $subtotal + $shippingCost;

        // Buat order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => $this->generateOrderNumber(),
            'subtotal' => $subtotal,
            'total' => $total,
            'shipping_cost' => $shippingCost,
            'recipient_name' => $request->recipient_name ?? auth()->user()->name,
            'recipient_phone' => $request->recipient_phone ?? auth()->user()->phone,
            'shipping_address' => $request->shipping_address ?? 'Pickup di Apotek',
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'district' => $request->district,
            'courier_name' => $request->courier ?? 'Pickup',
            'courier_service' => $request->courier === 'regular' ? 'Reguler' : ($request->courier === 'instant' ? 'Instan' : 'Ambil di Apotek'),
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        // Simpan bukti pembayaran jika ada
        if ($request->hasFile('payment_proof')) {
            $filename = $request->file('payment_proof')->store('payment_proofs', 'public');
            $order->update(['payment_proof' => $filename]);
        }

        // Order item
        OrderItem::create([
            'order_id' => $order->id,
            'obat_id' => $obat->id,
            'quantity' => $request->quantity,
            'price' => $obat->harga,
            'subtotal' => $subtotal,
        ]);

        $obat->decrement('stock', $request->quantity);

        DB::commit();
        return redirect()->route('orders.show', $order->id)->with('success', 'Pesanan berhasil dibuat!');

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Checkout error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Terjadi kesalahan saat memproses checkout.']);
    }
}

}
