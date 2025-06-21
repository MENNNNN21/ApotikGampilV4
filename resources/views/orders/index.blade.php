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
                    @foreach($orders as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $order->obat->nama }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($order->shipping_method) }}</td>
                            <td>{{ strtoupper($order->payment_method) }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                @elseif($order->status == 'paid')
                                    <span class="badge bg-success">Dibayar</span>
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
    @else
        <p>Tidak ada pesanan.</p>
    @endif
</div>
@endsection
