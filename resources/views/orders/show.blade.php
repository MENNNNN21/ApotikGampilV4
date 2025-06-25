@extends('layouts.app')
@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="container py-5">
    <h2>Detail Pesanan <span class="text-primary">{{ $order->order_number }}</span></h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span>Tanggal Pesan: {{ $order->created_at->format('d F Y') }}</span>
            <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
        </div>
        <div class="card-body">
            <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ strtoupper($order->payment_method) }}</p>
            <hr>

            {{-- Tampilkan bukti yang sudah diupload --}}
            @if($order->payment_proof)
                <div class="mb-3">
                    <h6>Bukti Pembayaran Anda:</h6>
                    <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 400px;">
                </div>
            @endif


            {{-- Form Upload hanya muncul jika status 'Menunggu Pembayaran' --}}
            @if($order->status == 'pending' && in_array($order->payment_method, ['qris', 'transfer']))
                <div class="card mt-4">
                    <div class="card-body bg-light">
                        <h5 class="card-title">Konfirmasi Pembayaran</h5>
                        <p>Silakan unggah bukti pembayaran Anda di sini.</p>
                        <form action="{{ route('orders.upload_proof', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">File Bukti Pembayaran</label>
                                <input class="form-control @error('payment_proof') is-invalid @enderror" type="file" id="payment_proof" name="payment_proof" required>
                                @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Unggah Bukti</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection