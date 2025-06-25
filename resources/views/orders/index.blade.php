@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Riwayat Pesanan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Metode Pengiriman</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Menggunakan @forelse lebih aman, tapi @foreach juga bisa --}}
                    @foreach($orders as $order)
                        <tr>
                            {{-- Nomor urut yang benar walaupun ada paginasi --}}
                            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            
                            {{-- KOLOM PRODUK YANG DIPERBAIKI --}}
                            <td>
                                {{-- 
                                    Langkah Pengecekan untuk menghindari error:
                                    1. Cek dulu apakah pesanan ini punya item ($order->items->isNotEmpty())
                                    2. Jika punya, cek apakah item pertama itu terhubung ke sebuah produk ($order->items->first()->obat)
                                --}}
                                @if($order->items->isNotEmpty() && $order->items->first()->obat)
                                    {{-- Jika semua aman, tampilkan nama produknya --}}
                                    {{ $order->items->first()->obat->name }} {{-- Ganti .name menjadi .nama jika nama kolom di db adalah 'nama' --}}
                                @else
                                    {{-- Jika tidak, tampilkan pesan aman --}}
                                    <span class="text-muted fst-italic">Produk tidak tersedia</span>
                                @endif
                            </td>

                            {{-- KOLOM JUMLAH YANG DIPERBAIKI --}}
                            <td>
                                {{-- Cukup cek apakah ada item di dalam pesanan ini --}}
                                @if($order->items->isNotEmpty())
                                    {{ $order->items->first()->quantity }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Kolom lain tetap sama --}}
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($order->shipping_method) }}</td>
                            <td>{{ strtoupper($order->payment_method) }}</td>
                            <td>
                                @if($order->status == 'pending_verification')
                                    <span class="badge bg-info text-dark">Verifikasi</span>
                                @elseif($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($order->status == 'paid' || $order->status == 'processing' || $order->status == 'shipped' )
                                    <span class="badge bg-success">Diproses</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-primary">Selesai</span>
                                @elseif($order->status == 'cancelled' || $order->status == 'rejected')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @else
                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td><a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Menampilkan link Paginasi --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>

    @else
        <div class="alert alert-info">
            <p class="mb-0">Tidak ada riwayat pesanan.</p>
        </div>
    @endif
</div>
@endsection