@extends('layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center">Riwayat Pesanan</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th class="d-none d-md-table-cell">Total</th>
                        <th class="d-none d-md-table-cell">Pengiriman</th>
                        <th class="d-none d-md-table-cell">Pembayaran</th>
                        <th>Status</th>
                        <th class="d-none d-md-table-cell">Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                            <td>
                                @if($order->items->isNotEmpty() && $order->items->first()->obat)
                                    {{ $order->items->first()->obat->name }}
                                @else
                                    <span class="text-muted fst-italic">Produk tidak tersedia</span>
                                @endif
                            </td>
                            <td>
                                @if($order->items->isNotEmpty())
                                    {{ $order->items->first()->quantity }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="d-none d-md-table-cell">{{ ucfirst($order->shipping_method) }}</td>
                            <td class="d-none d-md-table-cell">{{ strtoupper($order->payment_method) }}</td>
                            <td>
                                @php
                                    $badgeClass = match($order->status) {
                                        'pending_verification' => 'info text-dark',
                                        'pending' => 'warning text-dark',
                                        'paid', 'processing', 'shipped' => 'success',
                                        'completed' => 'primary',
                                        'cancelled', 'rejected' => 'danger',
                                        default => 'secondary'
                                    };
                                    $statusLabel = match($order->status) {
                                        'pending_verification' => 'Verifikasi',
                                        'pending' => 'Menunggu',
                                        'paid', 'processing', 'shipped' => 'Diproses',
                                        'completed' => 'Selesai',
                                        'cancelled', 'rejected' => 'Dibatalkan',
                                        default => ucfirst($order->status)
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                        {{-- Untuk Mobile View Info Tambahan --}}
                        <tr class="d-table-row d-md-none">
                            <td colspan="9" class="text-start small text-muted">
                                <div><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                                <div><strong>Pengiriman:</strong> {{ ucfirst($order->shipping_method) }}</div>
                                <div><strong>Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</div>
                                <div><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y H:i') }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="alert alert-info text-center">
            <p class="mb-0">Tidak ada riwayat pesanan.</p>
        </div>
    @endif
</div>
@endsection
