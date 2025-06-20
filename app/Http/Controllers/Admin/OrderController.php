<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar semua pesanan.
     */
   public function index(Request $request)
    {
        $query = Order::with('user');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter metode pembayaran
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Pencarian berdasarkan order_number atau nama user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%$search%");
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Untuk dropdown filter
        $statuses = [
            'pending', 'pending_verification', 'processing', 'ready_for_pickup',
            'shipped', 'completed', 'cancelled', 'payment_rejected', 'menunggu_pickup'
        ];
        $paymentMethods = ['qris', 'transfer', 'cod'];

        return view('admin.orders.index', compact('orders', 'statuses', 'paymentMethods'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    public function show($id)
    {
        $order = Order::with(['user', 'items.obat'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memverifikasi pembayaran dan mengubah status pesanan.
     */
    public function verifyPayment($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status != 'pending_verification') {
            return redirect()->route('admin.orders.show', $id)->with('error', 'Pesanan ini tidak dapat diverifikasi.');
        }

        // Tentukan status selanjutnya berdasarkan metode pengiriman
        $nextStatus = ($order->shipping_method == 'delivery') ? 'processing' : 'ready_for_pickup';
        
        $order->update(['status' => $nextStatus]);

        return redirect()->route('admin.orders.show', $id)->with('success', 'Pembayaran berhasil diverifikasi. Status pesanan diperbarui.');
    }

    /**
     * Menolak pembayaran dan mengubah status pesanan.
     */
    public function rejectPayment($id)
    {
        $order = Order::with('items')->findOrFail($id);

        if ($order->status != 'pending_verification') {
            return redirect()->route('admin.orders.show', $id)->with('error', 'Pesanan ini tidak dapat ditolak.');
        }

        DB::beginTransaction();
        try {
            $order->update(['status' => 'payment_rejected']);

            // Kembalikan stok produk
            foreach ($order->items as $item) {
                Obat::find($item->obat_id)->increment('stok', $item->quantity);
            }
            
            DB::commit();
            return redirect()->route('admin.orders.show', $id)->with('success', 'Pembayaran ditolak dan stok telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.orders.show', $id)->with('error', 'Gagal menolak pembayaran.');
        }
    }
}