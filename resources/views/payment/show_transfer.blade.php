@extends('layouts.app')
@section('title', 'Pembayaran Transfer Bank')

@section('content')
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h3 class="card-title">Instruksi Pembayaran</h3>
            <p>Silakan lakukan transfer sejumlah:</p>
            <h4 class="text-danger"><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></h4>
            <p>Ke salah satu rekening berikut:</p>

            <div class="border rounded p-3 mb-3">
                <h5>BANK BCA</h5>
                <p class="mb-0">No. Rekening: <strong>1234567890</strong></p>
                <p class="mb-0">Atas Nama: <strong>PT. Apotek Sehat Selalu</strong></p>
            </div>

            <div class="border rounded p-3">
                <h5>BANK MANDIRI</h5>
                <p class="mb-0">No. Rekening: <strong>0987654321</strong></p>
                <p class="mb-0">Atas Nama: <strong>PT. Apotek Sehat Selalu</strong></p>
            </div>

            <div class="alert alert-warning mt-4">
                <strong>Penting:</strong> Setelah melakukan transfer, mohon konfirmasi pembayaran Anda melalui WhatsApp atau halaman konfirmasi agar pesanan dapat segera kami proses.
            </div>
            {{-- Ganti link/tombol di bagian bawah --}}
<a href="{{ route('orders.show', $order) }}" class="btn btn-primary mt-3">
    Saya Sudah Bayar, Lanjut Konfirmasi
</a>
        </div>
    </div>
</div>
@endsection