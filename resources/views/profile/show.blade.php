@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-3">
                        @php
                            $avatar = auth()->user()->avatar ?? 'default.png';
                            
                            if ($avatar !== 'default.png') {
                                // Karena di DB sudah ada full path 'profile_pictures/filename.jpg'
                                $imagePath = asset('storage/' . $avatar);
                            } else {
                                $imagePath = asset('storage/profile_pictures/default.png');
                            }
                        @endphp
                        
                        <img src="{{ $imagePath }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;"
                             alt="Profile Picture"
                             onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                        <h5 class="card-title">{{ auth()->user()->name }}</h5>
                    </div>
                    <div class="list-group">
                        <a href="{{ route('profile.show') }}" 
                           class="list-group-item list-group-item-action active">
                            <i class="fas fa-user me-2"></i> Profil Saya
                        </a>
                        
                        <a href="{{ route('profile.edit') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-cog me-2"></i> Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Informasi Profil</h4>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                    </div>
                    
                    <table class="table">
                        <tr>
                            <th width="200">Nama Lengkap</th>
                            <td>{{ auth()->user()->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ auth()->user()->email }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td>{{ auth()->user()->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ auth()->user()->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bergabung Sejak</th>
                            <td>{{ auth()->user()->created_at->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Debug info (hapus setelah selesai debugging) --}}


@endsection