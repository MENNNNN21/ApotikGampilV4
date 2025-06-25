<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Memastikan semua method di controller ini hanya bisa diakses oleh admin yang sudah login.
     */
    public function __construct()
    {
        // Jika Anda menggunakan middleware 'auth:admin' atau sejenisnya,
        // Anda bisa menambahkannya di sini untuk melindungi seluruh controller.
        // Contoh: $this->middleware('auth:admin');
    }

    /**
     * Menampilkan daftar semua pesanan.
     */
    public function index(Request $request)
    {
        // Memulai query dasar, diurutkan dari yang terbaru
        $query = Order::latest();

        // Filter berdasarkan status jika ada parameter 'status' di URL
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan pencarian nomor pesanan
        if ($request->has('search') && $request->search != '') {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        // Ambil data dengan paginasi
        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail dari satu pesanan.
     */
    public function show(Order $order)
    {
        // Eager load relasi untuk menghindari N+1 query problem di view
        // Memuat item pesanan beserta produknya, dan data user yang memesan.
        $order->load('items.obat', 'user'); 

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Memverifikasi pembayaran yang sudah diunggah oleh pengguna.
     */
    public function verifyPayment(Order $order)
    {
        // Pastikan aksi hanya bisa dilakukan jika statusnya 'pending_verification'
        if ($order->status !== 'pending_verification') {
            return back()->with('error', 'Aksi tidak valid untuk status pesanan saat ini.');
        }

        // Ubah status menjadi 'processing' (sedang diproses)
        $order->update(['status' => 'processing']);

        // TODO: Tambahkan logika lain jika perlu, misal: mengirim notifikasi ke user.

        return redirect()->route('admin.orders.show', $order)->with('success', 'Pembayaran berhasil diverifikasi. Pesanan sekarang dalam status "Sedang Diproses".');
    }

    /**
     * Menolak bukti pembayaran yang diunggah pengguna.
     */
    public function rejectPayment(Order $order)
    {
        // Pastikan aksi hanya bisa dilakukan jika statusnya 'pending_verification'
        if ($order->status !== 'pending_verification') {
            return back()->with('error', 'Aksi tidak valid untuk status pesanan saat ini.');
        }

        // Hapus file bukti pembayaran dari storage
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        // Kembalikan status ke 'pending' dan hapus path file bukti pembayaran
        $order->update([
            'status' => 'pending',
            'payment_proof' => null,
        ]);

        // TODO: Tambahkan logika untuk mengembalikan stok produk.

        return redirect()->route('admin.orders.show', $order)->with('success', 'Pembayaran ditolak. Status pesanan dikembalikan ke "Menunggu Pembayaran".');
    }


    /**
     * Mengubah status pesanan secara umum (misal: dikirim, selesai).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|required_if:status,shipped|string|max:255',
        ]);

        $newStatus = $request->input('status');
        
        // Data untuk diupdate
        $updateData = ['status' => $newStatus];

        // Jika status diubah menjadi 'shipped', simpan nomor resi dan tanggal pengiriman
        if ($newStatus == 'shipped') {
            $updateData['tracking_number'] = $request->input('tracking_number');
            $updateData['shipped_at'] = now();
        }

        // Jika status diubah menjadi 'delivered', simpan tanggal diterima
        if ($newStatus == 'delivered') {
            $updateData['delivered_at'] = now();
        }

        $order->update($updateData);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}