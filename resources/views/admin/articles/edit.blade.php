@extends('admin/layouts.app')

@section('title', 'Edit Artikel')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Artikel</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.articles.update', $article->id) }}" method="POST" 
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="title">Judul Artikel</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $article->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content">Konten</label>
                    <textarea class="form-control @error('content') is-invalid @enderror" 
                              id="content" name="content" rows="10" required>{{ old('content', $article->content) }}</textarea>
                    @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Gambar</label>
                    @if($article->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $article->image) }}" 
                             alt="{{ $article->title }}" class="img-thumbnail" style="height: 200px">
                    </div>
                    @endif
                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                           id="image" name="image">
                    <small class="form-text text-muted">
                        Biarkan kosong jika tidak ingin mengubah gambar
                    </small>
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_published" 
                               name="is_published" value="1" 
                               {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_published">
                            Publikasikan
                        </label>
                    </div>
                </div>

                <div class="form-group" id="published_at_group" 
                     style="display: {{ $article->is_published ? 'block' : 'none' }};">
                    <label for="published_at">Tanggal Publikasi</label>
                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                           id="published_at" name="published_at" 
                           value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
                    @error('published_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });

    // Toggle published_at field visibility
    document.getElementById('is_published').addEventListener('change', function() {
        document.getElementById('published_at_group').style.display = 
            this.checked ? 'block' : 'none';
    });
</script>
@endpush