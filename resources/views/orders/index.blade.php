@extends('layouts.app')

@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            {{-- Profile Sidebar --}}
             <div class="card shadow-sm">
                <div class="card-body text-center">
                     <img src="{{ asset('storage/profile_pictures/' . (auth()->user()->avatar ?? 'default.png')) }}" 
                         class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;"
                         alt="Profile Picture">
                    <h5 class="card-title">{{ auth()->user()->name }}</h5>
                    <div class="list-group mt-3">
                        <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i> Profil Saya
                        </a>
                         <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-receipt me-2"></i> Riwayat Pesanan
                        </a>
                        <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog me-2"></i> Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <h2 class="mb-4">Riwayat Pesanan Saya</h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>No. Pesanan</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none fw-bold">
                                                {{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>{{ $order->getFormattedTotalAttribute() }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->getStatusColorAttribute() }}">
                                                {{ $order->getStatusLabelAttribute() }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <p class="text-muted">Anda belum memiliki riwayat pesanan.</p>
                                            <a href="{{ route('products') }}" class="btn btn-primary">Mulai Belanja</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection