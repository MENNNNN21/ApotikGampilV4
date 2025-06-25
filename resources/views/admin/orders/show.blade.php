@extends('admin.layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Pesanan</h1>
            <p class="text-muted mb-0">Nomor Pesanan: <span class="fw-bold text-primary">{{ $order->order_number }}</span></p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar Pesanan
        </a>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Kolom Kiri - Detail Utama --}}
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Pesanan Dibuat: {{ $order->created_at->format('d F Y, H:i') }}</h6>
                    <span class="badge bg-{{ $order->getStatusColorAttribute() }}">{{ $order->getStatusLabelAttribute() }}</span>
                </div>
                <div class="card-body">
                    {{-- Daftar Item --}}
                    <h5>Item Pesanan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->getFormattedPriceAttribute() }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">{{ $item->getFormattedSubtotalAttribute() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    {{-- Rincian Biaya --}}
                    <div class="row justify-content-end text-end">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Subtotal:</span>
                                <span>{{ $order->getFormattedSubtotalAttribute() }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Ongkos Kirim:</span>
                                <span>{{ $order->getFormattedShippingCostAttribute() }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5">
                                <span class="fw-bold text-primary">TOTAL:</span>
                                <span class="fw-bold text-primary">{{ $order->getFormattedTotalAttribute() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bukti Pembayaran & Aksi Admin --}}
            @if(in_array($order->payment_method, ['qris', 'transfer']))
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Verifikasi Pembayaran</h6>
                </div>
                <div class="card-body">
                    @if($order->payment_proof)
                        <h6 class="mb-3">Bukti Pembayaran Pengguna:</h6>
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank">
                            <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 400px; cursor: pointer;">
                        </a>
                        <hr>
                    @else
                        <div class="alert alert-warning">
                            Pengguna belum mengunggah bukti pembayaran.
                        </div>
                    @endif

                    {{-- Tombol Aksi Admin --}}
                    @if($order->status == 'pending_verification')
                        <p>Silakan verifikasi bukti pembayaran di atas.</p>
                        <div class="d-flex gap-2">
                             <form action="{{ route('admin.orders.verify', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Verifikasi Pembayaran
                                </button>
                            </form>
                             <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menolak pembayaran ini? Stok akan dikembalikan.')">
                                    <i class="fas fa-times"></i> Tolak Pembayaran
                                </button>
                            </form>
                        </div>
                    @elseif($order->status == 'pending')
                         <p class="text-muted">Menunggu pengguna untuk melakukan pembayaran dan mengunggah bukti.</p>
                    @else
                        <p class="text-muted">Aksi tidak diperlukan untuk status pesanan saat ini.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Kolom Kanan - Informasi Pelanggan & Pengiriman --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pelanggan</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong><br>{{ $order->user->name ?? 'Tamu' }}</p>
                    <p><strong>Email:</strong><br>{{ $order->user->email ?? '-' }}</p>
                    <p class="mb-0"><strong>Telepon:</strong><br>{{ $order->user->phone ?? 'Tidak ada' }}</p>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Pengiriman</h6>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Metode:</strong><br>
                        <span class="badge bg-info">{{ strtoupper($order->shipping_method ?? '-') }}</span>
                    </p>
                    <p><strong>Penerima:</strong><br>{{ $order->recipient_name }}</p>
                    <p><strong>Telepon Penerima:</strong><br>{{ $order->recipient_phone }}</p>
                    <p class="mb-0">
                        <strong>Alamat:</strong><br>
                        {{ $order->shipping_address ?? '-' }}
                        @if($order->district)
                            <br>{{ $order->district }}, {{ $order->city }}
                        @endif
                        @if($order->postal_code)
                            <br>{{ $order->postal_code }}
                        @endif
                    </p>
                    @if($order->notes)
                        <hr>
                        <p class="mb-0"><strong>Catatan:</strong><br><em>{{ $order->notes }}</em></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection