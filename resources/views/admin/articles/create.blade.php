@extends('admin/layouts.app')

@section('title', 'Tambah Artikel')

@section('content')

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Artikel</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
                @csrf
                <div class="form-group">
                    <label for="title">Judul Artikel <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Konten <span class="text-danger">*</span></label>
                    <!-- HAPUS required pada textarea karena CKEditor akan mengubah display: none -->
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" name="content" rows="10">{{ old('content') }}</textarea>
                    @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Gambar</label>
                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">
                        Format: JPG, JPEG, PNG. Maksimal 2MB.
                    </small>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>
let editorInstance;
ClassicEditor
    .create(document.querySelector('#content'))
    .then(editor => {
        editorInstance = editor;
    })
    .catch(error => {
        console.error(error);
    });

// Validasi konten harus diisi sebelum submit
document.getElementById('articleForm').addEventListener('submit', function(e){
    if (editorInstance) {
        let data = editorInstance.getData().trim();
        if (data === '') {
            alert('Konten artikel wajib diisi.');
            e.preventDefault();
        }
    }
});
</script>
@endpush