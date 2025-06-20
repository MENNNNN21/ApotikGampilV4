@extends('layouts.app')

@section('title', 'Pesanan Berhasil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="h2 fw-bold text-success mb-3">Pesanan Berhasil Dibuat!</h2>
                    <p class="text-muted">Terima kasih telah berbelanja di Apotik Gampil. Pesanan Anda sedang kami proses.</p>
                    
                    <div class="alert alert-info mt-4">
                        Nomor Pesanan Anda:
                        <h4 class="fw-bold mb-0 mt-2">{{ $order->order_number }}</h4>
                    </div>

                    <div class="order-summary mt-4 text-start">
                        <h5 class="mb-3">Ringkasan Pesanan</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">{{ $item->product_name }}</span> ({{ $item->quantity }}x)
                                </div>
                                <span>{{ $item->getFormattedSubtotalAttribute() }}</span>
                            </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Subtotal</span>
                                <span>{{ $order->getFormattedSubtotalAttribute() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Ongkos Kirim ({{ $order->courier_name }} - {{ $order->courier_service }})</span>
                                <span>{{ $order->getFormattedShippingCostAttribute() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center fw-bold fs-5">
                                <span>Total</span>
                                <span class="text-primary">{{ $order->getFormattedTotalAttribute() }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-5">
                        <a href="{{ route('products') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-shopping-bag me-2"></i>Lanjut Belanja
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">
                            <i class="fas fa-receipt me-2"></i>Lihat Riwayat Pesanan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection