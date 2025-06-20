@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h2 mb-1">Detail Pesanan</h2>
            <p class="text-muted">
                Nomor Pesanan: <span class="fw-bold text-primary">{{ $order->order_number }}</span>
            </p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Riwayat
        </a>
    </div>

    <div class="row g-4">
        {{-- Order Details --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Pesanan Dibuat pada {{ $order->created_at->format('d F Y, H:i') }}</h5>
                    <span class="badge bg-{{ $order->getStatusColorAttribute() }} fs-6">
                        {{ $order->getStatusLabelAttribute() }}
                    </span>
                </div>
                <div class="card-body">
                    {{-- Item List --}}
                    <h6>Daftar Produk</h6>
                    <table class="table table-borderless">
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    @if($item->obat && $item->obat->image)
                                    <img src="{{ asset('storage/' . $item->obat->image) }}" class="img-fluid rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="{{ $item->product_name }}">
                                    @endif
                                </td>
                                <td>
                                    <p class="mb-0 fw-bold">{{ $item->product_name }}</p>
                                    <small class="text-muted">{{ $item->quantity }} x {{ $item->getFormattedPriceAttribute() }}</small>
                                </td>
                                <td class="text-end fw-bold">{{ $item->getFormattedSubtotalAttribute() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    {{-- Order Totals --}}
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <dl class="row text-end">
                                <dt class="col-sm-6">Subtotal</dt>
                                <dd class="col-sm-6">{{ $order->getFormattedSubtotalAttribute() }}</dd>

                                <dt class="col-sm-6">Ongkos Kirim</dt>
                                <dd class="col-sm-6">{{ $order->getFormattedShippingCostAttribute() }}</dd>

                                <dt class="col-sm-6 fs-5">Total</dt>
                                <dd class="col-sm-6 fw-bold fs-5 text-primary">{{ $order->getFormattedTotalAttribute() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipping Info --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">{{ $order->recipient_name }}</h6>
                    <p class="text-muted mb-2">{{ $order->recipient_phone }}</p>
                    <p class="text-muted lh-base">
                        {{ $order->shipping_address }},<br>
                        {{ $order->district }}, {{ $order->city }},<br>
                        {{ $order->postal_code }}
                    </p>
                    <hr>
                    <p class="mb-1"><strong class="d-block">Kurir:</strong> {{ $order->courier_name }} - {{ $order->courier_service }}</p>
                    @if($order->waybill_id)
                        <p class="mb-0"><strong class="d-block">No. Resi:</strong> {{ $order->waybill_id }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection