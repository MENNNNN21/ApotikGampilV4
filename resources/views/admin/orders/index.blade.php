@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Daftar Pesanan</h2>

    {{-- Filter dan Pencarian --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Cari No. Pesanan / Nama User" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="payment_method" class="form-select">
                <option value="">-- Semua Metode --</option>
                @foreach($paymentMethods as $method)
                    <option value="{{ $method }}" {{ request('payment_method') == $method ? 'selected' : '' }}>
                        {{ strtoupper($method) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Nama User</th>
                <th>Total Harga</th>
                <th>Metode Pembayaran</th>
                <th>Status</th>
                <th>Tanggal Pesan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td>Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                <td>{{ strtoupper($order->payment_method) }}</td>
                <td>{{ ucwords(str_replace('_', ' ', $order->status)) }}</td>
                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">Lihat Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada pesanan ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection