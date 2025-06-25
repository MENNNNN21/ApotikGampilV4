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
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


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
              'weight' => $obat->weight,
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
       // Di dalam metode processCheckout()
$obat = Obat::findOrFail($request->obat_id); // Anda sudah punya data obat di sini

OrderItem::create([
    'order_id' => $order->id,
    'obat_id' => $obat->id,
    'product_name' => $obat->name, // <--- TAMBAHKAN BARIS INI
    'quantity' => $request->quantity,
    'price' => $obat->harga,
    'subtotal' => $subtotal,
    'weight' => $obat->weight,
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

public function showCheckout(Request $request, Obat $product)
    {
        // 1. Validasi data yang masuk dari form sebelumnya
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1|max:' . $product->stock,
            'shipping_method' => 'required|in:instant,pickup',
            'recipient_name' => 'required_if:shipping_method,instant|string|max:255',
            'recipient_phone' => 'required_if:shipping_method,instant|string|max:20',
            'shipping_address' => 'required_if:shipping_method,instant|string',
            'postal_code' => 'required_if:shipping_method,instant|string|max:10',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        // 2. Kumpulkan semua data yang divalidasi
        $validatedData = $validator->validated();
        
        // 3. Hitung total
        $subtotal = $product->harga * $validatedData['quantity'];
        
        // Ongkir ditanggung pembeli untuk 'instant' dan gratis untuk 'pickup'
        $shippingCost = 0; 

        $total = $subtotal + $shippingCost;

        // 4. Gabungkan semua data untuk dikirim ke view checkout
        $checkoutData = [
            'product' => $product,
            'quantity' => $validatedData['quantity'],
            'shipping_method' => $validatedData['shipping_method'],
            'recipient_name' => $validatedData['recipient_name'] ?? auth()->user()->name,
            'recipient_phone' => $validatedData['recipient_phone'] ?? auth()->user()->phone,
            'shipping_address' => $validatedData['shipping_address'] ?? 'Ambil di Apotek',
            'postal_code' => $validatedData['postal_code'] ?? '-',
            'notes' => $validatedData['notes'] ?? '',
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total
        ];

        // 5. Tampilkan view checkout dengan membawa semua data
        // Anda perlu membuat file view baru: 'resources/views/checkout.blade.php'
        return view('checkout', ['data' => $checkoutData]);
    }

    // di dalam file app/Http/Controllers/OrderController.php

public function processPayment(Request $request)
    {
        // 1. Validasi data
        $request->validate([
            'payment_method' => 'required|in:qris,transfer,cash',
            'product_id' => 'required|exists:obat,id',
            'quantity' => 'required|integer|min:1',
            'subtotal' => 'required|numeric',
            'total' => 'required|numeric',
            // ... validasi lain jika ada ...
        ]);

        // 2. Buat pesanan di database menggunakan struktur Anda
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(), // Menggunakan method dari model Anda
            'user_id' => Auth::id(),
            'subtotal' => $request->subtotal,
            'shipping_cost' => 0, // Ongkir instan ditanggung pembeli, pickup gratis
            'total' => $request->total,
            
            // Kolom baru yang sudah kita tambahkan
            'payment_method' => $request->payment_method,
            'shipping_method' => $request->shipping_method,
            
            // Status awal, sesuai dengan match di model Anda
            'status' => 'pending', 
            
            // Detail penerima dari form
            'recipient_name' => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'shipping_address' => $request->shipping_address,
            'postal_code' => $request->postal_code,
            'notes' => $request->notes,
            
            // Kolom ini bisa diisi null karena tidak menggunakan courier
            'city' => null, 
            'district' => null,
            'courier_name' => null,
            'courier_service' => null,
        ]);

// Simpan detail item ke tabel order_items
       // Di dalam metode processPayment()
$obat = Obat::find($request->product_id); // Anda sudah punya data obat di sini

OrderItem::create([
    'order_id' => $order->id,
    'obat_id' => $obat->id,
    'product_name' => $obat->name, // <--- TAMBAHKAN BARIS INI
    'quantity' => $request->quantity,
    'price' => $obat->harga,
      'weight' => $obat->weight,
    'subtotal' => $request->subtotal
]);
        
        // 3. Arahkan berdasarkan metode pembayaran
        if ($request->payment_method == 'qris') {
            // Ubah status jika perlu, lalu tampilkan view
            $order->update(['status' => 'pending']); // Status `pending` sudah sesuai 'Menunggu Pembayaran'
            return view('payment.show_qris', ['order' => $order]);

        } elseif ($request->payment_method == 'transfer') {
            $order->update(['status' => 'pending']);
            return view('payment.show_transfer', ['order' => $order]);

        } elseif ($request->payment_method == 'cash') {
            $order->update(['status' => 'pending']);
            return view('payment.success_cash', ['order' => $order]);
        }
        
        return redirect()->route('home')->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
    }
    /**
 * Menampilkan halaman detail pesanan.
 */
public function show($id)
{
    // Langkah 1: Ambil data pesanan dari database secara manual.
    // findOrFail akan otomatis menampilkan error 404 jika order tidak ditemukan.
    $order = Order::findOrFail($id);

    // Langkah 2: Lakukan pengecekan kepemilikan.
    // Kode ini sama persis seperti sebelumnya.
    if (auth()->id() !== $order->user_id) {
        abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
    }

    // Langkah 3: Tampilkan view dengan data pesanan yang sudah pasti benar.
    return view('orders.show', compact('order'));
}

/**
 * Memproses upload bukti pembayaran.
 */
public function uploadProof(Request $request, Order $order)
{
    // Pastikan user yang login hanya bisa upload untuk order miliknya sendiri
    if (auth()->id() !== $order->user_id) {
        abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
    }

    // Validasi request
    $request->validate([
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
    ]);

    // Hapus bukti lama jika ada
    if ($order->payment_proof) {
        Storage::disk('public')->delete($order->payment_proof);
    }

    // Simpan file baru
    $path = $request->file('payment_proof')->store('payment_proofs', 'public');

    // Update database
    $order->update([
        'payment_proof' => $path,
        'status' => 'pending_verification', // Ubah status
    ]);

    return redirect()->route('orders.show', $order)->with('success', 'Bukti pembayaran berhasil diunggah. Pesanan Anda akan segera kami verifikasi.');
}

public function index()
    {
        // 1. Ambil semua pesanan milik pengguna yang sedang login.
        //    - `where('user_id', auth()->id())` memastikan pengguna hanya melihat pesanannya sendiri.
        //    - `latest()` mengurutkan dari yang terbaru ke yang terlama.
        //    - `paginate(10)` membatasi 10 pesanan per halaman (baik untuk performa).
        $orders = Order::with('items.obat') // <--- PERUBAHAN UTAMA DI SINI
                   ->where('user_id', auth()->id())
                   ->latest()
                   ->paginate(10);

    return view('orders.index', compact('orders'));
    }
}
