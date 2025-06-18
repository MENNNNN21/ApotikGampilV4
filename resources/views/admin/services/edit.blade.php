@extends('admin/layouts.app')

@section('title', 'Edit Layanan')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Layanan</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.services.update', $service->id) }}" method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Nama Layanan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $service->name) }}" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4" required>{{ old('description', $service->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="whatsapp_template">Template Pesan WhatsApp <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('whatsapp_template') is-invalid @enderror" 
                              id="whatsapp_template" name="whatsapp_template" rows="3" required>{{ old('whatsapp_template', $service->whatsapp_template) }}</textarea>
                    <small class="form-text text-muted">
                        Gunakan {name} untuk nama pelanggan dan {service} untuk nama layanan.
                    </small>
                    @error('whatsapp_template')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Gambar</label>
                    @if($service->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             alt="{{ $service->name }}" class="img-thumbnail" style="height: 200px">
                    </div>
                    @endif
                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">
                        Biarkan kosong jika tidak ingin mengubah gambar. Format: JPG, JPEG, PNG. Maksimal 2MB.
                    </small>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.services.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection