@extends('admin.layouts.app')

@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Detail Pesanan</h1>
            <p class="text-muted mb-0">Nomor Pesanan: <span class="fw-bold text-primary">{{ $order->order_number }}</span></p>
        
@if($order->shipping_method === 'pickup' && !$order->is_picked_up)
    <form action="{{ route('admin.orders.pickedup', $order->id) }}" method="POST" onsubmit="return confirm('Yakin pesanan sudah diambil?')">
        @csrf
        <button class="btn btn-success mt-3">Tandai Sudah Diambil</button>
    </form>
@endif

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
                        <p class="text-muted">Pembayaran telah diverifikasi.</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Status Pengiriman & Aksi Admin --}}
            @if(in_array($order->status, ['processing', 'shipped']))
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @if($order->shipping_method == 'pickup')
                            Kelola Status Pengambilan
                        @else
                            Kelola Status Pengiriman
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    @if($order->status == 'processing')
                        @if($order->shipping_method == 'pickup')
                            {{-- Untuk pengambilan di apotek --}}
                            <div class="alert alert-info">
                                <i class="fas fa-store"></i> Pesanan sudah diverifikasi dan siap untuk diambil di apotek.
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Petunjuk:</strong> Pastikan pelanggan membawa bukti pembayaran dan identitas saat mengambil pesanan.
                            </div>
                            
                            {{-- Tombol langsung untuk pickup --}}
                            <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit" class="btn btn-success btn-lg" 
                                        onclick="return confirm('Apakah pesanan ini sudah diambil oleh pelanggan di apotek?')">
                                    <i class="fas fa-hand-holding"></i> Tandai Sebagai Sudah Diambil
                                </button>
                            </form>
                        @else
                            {{-- Untuk pengiriman --}}
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Pesanan sudah diverifikasi dan siap untuk dikirim.
                            </div>
                            
                            {{-- Form untuk mengubah status ke "shipped" --}}
                            <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="shipped">
                                
                                <div class="mb-3">
                                    <label for="tracking_number" class="form-label">Nomor Resi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tracking_number" name="tracking_number" 
                                           placeholder="Masukkan nomor resi pengiriman" required>
                                    <div class="form-text">Nomor resi akan dikirimkan ke pelanggan untuk tracking pengiriman.</div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-shipping-fast"></i> Kirim Pesanan
                                </button>
                            </form>
                        @endif

                    @elseif($order->status == 'shipped')
                        <div class="alert alert-primary">
                            <i class="fas fa-shipping-fast"></i> Pesanan sedang dalam pengiriman.
                            @if($order->tracking_number)
                                <br><strong>Nomor Resi:</strong> {{ $order->tracking_number }}
                            @endif
                            @if($order->shipped_at)
                                <br><strong>Tanggal Kirim:</strong> {{ $order->shipped_at->format('d F Y, H:i') }}
                            @endif
                        </div>
                        
                        {{-- Tombol untuk mengubah status ke "delivered" --}}
                        <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <input type="hidden" name="status" value="delivered">
                            <button type="submit" class="btn btn-success" 
                                    onclick="return confirm('Apakah Anda yakin pesanan ini sudah sampai ke pelanggan?')">
                                <i class="fas fa-check-circle"></i> Tandai Sebagai Terkirim
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endif

            {{-- Status Pesanan Selesai --}}
            @if($order->status == 'delivered')
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        @if($order->shipping_method == 'pickup')
                            Status Pengambilan
                        @else
                            Status Pengiriman
                        @endif
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        @if($order->shipping_method == 'pickup')
                            <i class="fas fa-check-circle"></i> Pesanan telah berhasil diambil oleh pelanggan di apotek.
                        @else
                            <i class="fas fa-check-circle"></i> Pesanan telah berhasil dikirim dan diterima oleh pelanggan.
                        @endif
                        @if($order->delivered_at)
                            <br><strong>Tanggal {{ $order->shipping_method == 'pickup' ? 'Diambil' : 'Diterima' }}:</strong> {{ $order->delivered_at->format('d F Y, H:i') }}
                        @endif
                    </div>
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
                        <span class="badge bg-{{ $order->shipping_method == 'pickup' ? 'success' : 'info' }}">
                            {{ $order->shipping_method == 'pickup' ? 'AMBIL DI APOTEK' : strtoupper($order->shipping_method ?? '-') }}
                        </span>
                    </p>
                    @if($order->shipping_method != 'pickup')
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
                    @else
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-store"></i> <strong>Pengambilan di Apotek</strong><br>
                            Pelanggan akan mengambil pesanan langsung di apotek.<br>
                            <small class="text-muted">Pastikan pelanggan membawa bukti pembayaran dan identitas.</small>
                        </div>
                    @endif
                    @if($order->notes)
                        <hr>
                        <p class="mb-0"><strong>Catatan:</strong><br><em>{{ $order->notes }}</em></p>
                    @endif
                </div>
            </div>

            {{-- Timeline Status --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Timeline Status</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $order->status != 'pending' ? 'completed' : 'current' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pesanan Dibuat</h6>
                                <p class="timeline-text">{{ $order->created_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        @if(in_array($order->status, ['pending_verification', 'processing', 'shipped', 'delivered']))
                        <div class="timeline-item {{ !in_array($order->status, ['pending_verification']) ? 'completed' : 'current' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Pembayaran Diverifikasi</h6>
                                <p class="timeline-text">Status: Sedang Diproses</p>
                            </div>
                        </div>
                        @endif
                        
                        @if(in_array($order->status, ['shipped', 'delivered']))
                        <div class="timeline-item {{ $order->status != 'shipped' ? 'completed' : 'current' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">
                                    {{ $order->shipping_method == 'pickup' ? 'Pesanan Siap Diambil' : 'Pesanan Dikirim' }}
                                </h6>
                                @if($order->shipped_at)
                                    <p class="timeline-text">{{ $order->shipped_at->format('d F Y, H:i') }}</p>
                                @endif
                                @if($order->tracking_number && $order->shipping_method != 'pickup')
                                    <p class="timeline-text"><strong>Resi:</strong> {{ $order->tracking_number }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'delivered')
                        <div class="timeline-item completed">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">
                                    {{ $order->shipping_method == 'pickup' ? 'Pesanan Diambil' : 'Pesanan Diterima' }}
                                </h6>
                                @if($order->delivered_at)
                                    <p class="timeline-text">{{ $order->delivered_at->format('d F Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    width: 2px;
    height: calc(100% - 10px);
    background-color: #e3e6f0;
}

.timeline-item.completed:not(:last-child)::before {
    background-color: #1cc88a;
}

.timeline-item.current:not(:last-child)::before {
    background-color: #36b9cc;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: #e3e6f0;
    border: 2px solid #fff;
}

.timeline-item.completed .timeline-marker {
    background-color: #1cc88a;
}

.timeline-item.current .timeline-marker {
    background-color: #36b9cc;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}

.d-flex.gap-2 > * {
    margin-right: 10px;
}

.d-flex.gap-2 > *:last-child {
    margin-right: 0;
}
</style>
@endsection