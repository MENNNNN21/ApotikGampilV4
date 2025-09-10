@extends('layouts.app')
@section('title', 'Pesanan Diterima')

@section('content')
<div class="container py-5 text-center">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h3 class="card-title">Pesanan Anda Telah Diterima!</h3>
            <p>Terima kasih telah berbelanja.</p>
            <hr>
            <p>Order ID Anda:</p>
            <h4><strong>{{ $order->id }}</strong></h4>
            <div class="alert alert-info mt-3">
                Silakan tunjukkan Order ID ini dan lakukan pembayaran tunai di kasir saat Anda mengambil pesanan di apotek.
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary">Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection