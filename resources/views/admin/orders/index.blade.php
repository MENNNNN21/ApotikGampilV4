@extends('admin.layouts.app')

@section('title', 'Daftar Pesanan')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Pesanan</h1>
    </div>

    {{-- Card untuk Tabel Pesanan --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Seluruh Pesanan</h6>
        </div>
        <div class="card-body">

            {{-- Form untuk Filter dan Pencarian --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <form action="{{ route('admin.orders.index') }}" method="GET" class="form-inline">
                        <div class="form-group me-2 mb-2">
                            <label for="status" class="sr-only">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>
                        <div class="form-group me-2 mb-2 flex-grow-1">
                             <label for="search" class="sr-only">Cari</label>
                             <input type="text" name="search" id="search" class="form-control w-100" placeholder="Cari nomor pesanan..." value="{{ request('search') }}">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-search"></i> Terapkan
                        </button>
                         <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mb-2 ms-2">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </form>
                </div>
            </div>

            {{-- Tabel Data Pesanan --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Metode Pembayaran</th>
                            <th class="text-center">Status</th>
                            <th>Tanggal Pesan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="font-weight-bold">
                                    <a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a>
                                </td>
                                <td>{{ $order->user->name ?? 'Tamu' }}</td>
                                <td class="text-end">{{ $order->getFormattedTotalAttribute() }}</td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ strtoupper($order->payment_method) }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $order->getStatusColorAttribute() }}">{{ $order->getStatusLabelAttribute() }}</span>
                                </td>
                                <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <p class="my-4">Tidak ada data pesanan yang ditemukan.</p>
                                    @if(request()->has('status') || request()->has('search'))
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm">Lihat Semua Pesanan</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginasi --}}
            <div class="d-flex justify-content-end">
                {{ $orders->links() }}
            </div>

        </div>
    </div>
</div>
@endsection