<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout dengan detail produk.
     */
    public function showCheckoutPage(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $obat = Obat::findOrFail($request->obat_id);
        $quantity = $request->quantity;

        // Cek apakah stok mencukupi
        if ($obat->stok < $quantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        return view('checkout.payment', compact('obat', 'quantity'));
    }

    /**
     * Memproses pesanan dari halaman checkout.
     */
    public function processOrder(Request $request)
    {
        // Aturan validasi dasar
        $rules = [
            'obat_id' => 'required|exists:obat,id',
            'quantity' => 'required|integer|min:1',
            'shipping_method' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:qris,transfer,cod',
        ];

        // Validasi kondisional berdasarkan metode pengiriman
        if ($request->shipping_method == 'delivery') {
            $rules += [
                'recipient_name' => 'required|string|max:255',
                'recipient_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string',
                'postal_code' => 'required|string|max:10',
                'city' => 'required|string|max:100',
                'district' => 'required|string|max:100',
            ];
        }

        // Validasi kondisional berdasarkan metode pembayaran
        if (in_array($request->payment_method, ['qris', 'transfer'])) {
            $rules['payment_proof'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }
        
        // Validasi metode pembayaran untuk metode pengiriman
        if ($request->shipping_method == 'pickup' && $request->payment_method != 'cod') {
             return redirect()->back()->withErrors(['payment_method' => 'Metode pembayaran untuk "Ambil di Apotek" harus "Bayar di Tempat".'])->withInput();
        }
        
        if ($request->shipping_method == 'delivery' && $request->payment_method == 'cod') {
             return redirect()->back()->withErrors(['payment_method' => 'Metode pembayaran "Bayar di Tempat" hanya untuk "Ambil di Apotek".'])->withInput();
        }


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Mulai transaksi database untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            $obat = Obat::findOrFail($request->obat_id);
            $quantity = $request->quantity;

            // Cek stok sekali lagi sebelum proses
            if ($obat->stok < $quantity) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }

            // Hitung total
            $subtotal = $obat->harga * $quantity;
            $shipping_cost = ($request->shipping_method == 'delivery') ? 15000 : 0; // Contoh biaya kirim statis
            $total = $subtotal + $shipping_cost;

            // Simpan bukti pembayaran jika ada
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('public/payment_proofs');
            }

            // Tentukan status awal
            $status = 'pending_verification';
            if ($request->payment_method == 'cod') {
                $status = 'menunggu_pickup';
            }

            // Buat pesanan baru
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping_cost,
                'total' => $total,
                'status' => $status,
                'shipping_method' => $request->shipping_method,
                'payment_method' => $request->payment_method,
                'payment_proof' => $paymentProofPath,
                'recipient_name' => $request->recipient_name,
                'recipient_phone' => $request->recipient_phone,
                'shipping_address' => $request->shipping_address,
                'postal_code' => $request->postal_code,
                'city' => $request->city,
                'district' => $request->district,
            ]);

            // Buat item pesanan
            OrderItem::create([
                'order_id' => $order->id,
                'obat_id' => $obat->id,
                'quantity' => $quantity,
                'price' => $obat->harga,
            ]);

            // Kurangi stok produk
            $obat->decrement('stok', $quantity);

            DB::commit(); // Jika semua berhasil, simpan perubahan

            return redirect()->route('home')->with('success', 'Pesanan Anda berhasil dibuat dan sedang menunggu verifikasi.'); // Arahkan ke halaman sukses

        } catch (\Exception $e) {
            DB::rollBack(); // Jika terjadi error, batalkan semua perubahan
            // Log error jika perlu: Log::error($e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }
}