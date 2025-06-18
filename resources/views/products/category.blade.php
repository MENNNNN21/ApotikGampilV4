@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="container py-5">
    <h2 class="mb-4">{{ $category->name }}</h2>
    <div class="row">
        @foreach($medicines as $medicine)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="{{ asset('storage/' . $medicine->image) }}" class="card-img-top" alt="{{ $medicine->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $medicine->name }}</h5>
                    <p class="card-text">{{ Str::limit($medicine->description, 100) }}</p>
                    <a href="{{ route('products.show', $medicine->id) }}" class="btn btn-primary">Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection