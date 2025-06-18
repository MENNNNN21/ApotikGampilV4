@extends('layouts.app')

@section('title', $medicine->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $medicine->image) }}" class="img-fluid rounded" alt="{{ $medicine->name }}">
        </div>
        <div class="col-md-6">
            <h2>{{ $medicine->name }}</h2>
            <div class="mt-4">
                <h4>Deskripsi</h4>
                <p>{{ $medicine->deskripsi }}</p>
            </div>
            <div class="mt-4">
                <h4>Dosis</h4>
                <p>{{ $medicine->dosis }}</p>
            </div>
            <div class="mt-4">
                <h4>Efek Samping</h4>
                <p>{{ $medicine->efek_samping }}</p>
            </div>
            <div class="mt-4">
                <h4>Harga</h4>
                <p>Rp {{ number_format($medicine->harga, 0, ',', '.') }}</p>
            </div>
            <a href="#" class="btn btn-success">Beli Sekarang</a>
        </div>
    </div>
</div>
@endsection