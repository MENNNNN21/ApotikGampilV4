@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Detail Pesanan #{{ $order->order_number }}</h2>

    {{-- Informasi User --}}
    <div class="mb-3">
        <strong>Nama User:</strong> {{ $order->user->name ?? '-' }}<br>
        <strong>Email:</strong> {{ $order->user->email ?? '-' }}
    </div>

    {{-- Item Pesanan --}}
    <div class="mb-3">
        <h5>Item Pesanan:</h5>
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->nama ?? '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Detail Pengiriman --}}
    <div class="mb-3">
        <h5>Detail Pengiriman:</h5>
        <strong>Metode:</strong> {{ strtoupper($order->shipping_method) }}<br>
        @if($order->shipping_method == 'delivery')
            <strong>Nama Penerima:</strong> {{ $order->recipient_name }}<br>
            <strong>Telepon:</strong> {{ $order->recipient_phone }}<br>
            <strong>Alamat:</strong> {{ $order->shipping_address }}, {{ $order->district }}, {{ $order->city }}, {{ $order->postal_code }}
        @else
            <em>Ambil di Apotek</em>
        @endif
    </div>

    {{-- Detail Pembayaran --}}
    <div class="mb-3">
        <h5>Detail Pembayaran:</h5>
        <strong>Metode:</strong> {{ strtoupper($order->payment_method) }}<br>
        <strong>Subtotal:</strong> Rp{{ number_format($order->subtotal, 0, ',', '.') }}<br>
        <strong>Ongkir:</strong> Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}<br>
        <strong>Total:</strong> Rp{{ number_format($order->total, 0, ',', '.') }}<br>
        @if(in_array($order->payment_method, ['qris', 'transfer']) && $order->payment_proof)
            <div class="mt-2">
                <strong>Bukti Pembayaran:</strong><br>
                <img src="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" alt="Bukti Pembayaran" style="max-width:300px;">
            </div>
        @endif
    </div>

    {{-- Status & Aksi --}}
    <div class="mb-3">
        <strong>Status:</strong> {{ ucwords(str_replace('_', ' ', $order->status)) }}
    </div>

    @if($order->status == 'pending_verification')
        <form action="{{ route('admin.orders.verify', $order->id) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-success">Verifikasi Pembayaran</button>
        </form>
        <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST" style="display:inline-block;">
            @csrf
            <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
        </form>
    @endif

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Pesanan</a>
</div>
@endsection