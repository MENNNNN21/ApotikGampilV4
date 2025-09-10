@extends('layouts.app')
@section('title', 'Pembayaran QRIS')

@section('content')
<div class="container py-5 text-center">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h3 class="card-title">Scan untuk Membayar</h3>
            <p>Order ID: <strong>{{ $order->id }}</strong></p>
            <p class="text-danger">Total Pembayaran: <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></p>

            {{-- Ganti 'qris-image.png' dengan nama file gambar QRIS Anda --}}
            <img src="{{ asset('img/qris-image.jpg') }}" alt="QRIS Code" class="img-fluid my-3">

            <div class="alert alert-info">
                Silakan scan kode QR di atas menggunakan aplikasi E-Wallet atau Mobile Banking Anda.
                Pembayaran akan terverifikasi secara otomatis.
            </div>
         {{-- Ganti link/tombol di bagian bawah --}}
<a href="{{ route('orders.show', $order) }}" class="btn btn-primary mt-3">
    Saya Sudah Bayar, Lanjut Konfirmasi
</a>
        </div>
    </div>
</div>
@endsection