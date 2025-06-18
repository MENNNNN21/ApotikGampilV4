@extends('layouts.app')

@section('title', 'Artikel')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h2 class="display-4 fw-bold text-primary mb-3">Artikel Kesehatan</h2>
        <p class="lead text-muted">Temukan informasi kesehatan terpercaya dan terkini</p>
        <div class="mx-auto" style="width: 100px; height: 4px; background: linear-gradient(45deg, #007bff, #28a745); border-radius: 2px;"></div>
    </div>

    <!-- Articles Grid -->
    <div class="row g-4">
        @foreach($articles as $article)
        <div class="col-lg-4 col-md-6">
            <div class="article-card card h-100 shadow-sm border-0 overflow-hidden position-relative">
                <!-- Card Hover Effect -->
                <div class="card-hover-overlay position-absolute w-100 h-100" style="background: linear-gradient(45deg, rgba(0,123,255,0.1), rgba(40,167,69,0.1)); opacity: 0; transition: opacity 0.3s ease; z-index: 1;"></div>
                
                <!-- Image Container -->
                <div class="position-relative overflow-hidden" style="height: 200px;">
                    <img src="{{ asset('storage/' . $article->avatar) }}" 
                         class="card-img-top w-100 h-100 object-fit-cover transition-transform" 
                         alt="{{ $article->title }}"
                         style="transition: transform 0.3s ease;">
                    
                    <!-- Image Overlay -->
                    <div class="position-absolute top-0 start-0 w-100 h-100" 
                         style="background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.1) 100%);"></div>
                </div>
                
                <!-- Card Body -->
                <div class="card-body p-4 d-flex flex-column position-relative" style="z-index: 2;">
                    <h5 class="card-title fw-bold text-dark mb-3 lh-base">{{ $article->title }}</h5>
                    <p class="card-text text-muted flex-grow-1 mb-4">{!! Str::limit($article->content, 100) !!}</p>
                    
                    <!-- Read More Button -->
                    <div class="mt-auto">
                        <a href="{{ route('articles.show', $article->slug) }}" 
                           class="btn btn-primary btn-sm px-4 py-2 rounded-pill text-decoration-none fw-medium position-relative overflow-hidden">
                            <span class="position-relative" style="z-index: 2;">Baca Selengkapnya</span>
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-0 transition-opacity" style="transition: opacity 0.3s ease;"></div>
                        </a>
                    </div>
                </div>
                
                <!-- Card Bottom Accent -->
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 3px; background: linear-gradient(90deg, #007bff, #28a745);"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        <div class="pagination-wrapper">
            {{ $articles->links() }}
        </div>
    </div>
</div>

<style>
/* Custom Styles */
.article-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 15px !important;
}

.article-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
}

.article-card:hover .card-hover-overlay {
    opacity: 1 !important;
}

.article-card:hover img {
    transform: scale(1.05);
}

.article-card:hover .btn {
    background-color: #0056b3 !important;
    transform: translateY(-1px);
}

.article-card .btn:hover .bg-dark {
    opacity: 0.1 !important;
}

.object-fit-cover {
    object-fit: cover;
}

.transition-transform {
    transition: transform 0.3s ease;
}

.transition-opacity {
    transition: opacity 0.3s ease;
}

/* Pagination Styling */
.pagination-wrapper .pagination {
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.pagination-wrapper .page-link {
    border: none;
    padding: 12px 20px;
    color: #007bff;
    background-color: #fff;
    transition: all 0.3s ease;
}

.pagination-wrapper .page-link:hover {
    background-color: #007bff;
    color: #fff;
    transform: translateY(-1px);
}

.pagination-wrapper .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .article-card {
        margin-bottom: 1.5rem;
    }
    
    .article-card:hover {
        transform: translateY(-4px);
    }
}

/* Loading Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.article-card {
    animation: fadeInUp 0.6s ease forwards;
}

.article-card:nth-child(2) {
    animation-delay: 0.1s;
}

.article-card:nth-child(3) {
    animation-delay: 0.2s;
}

.article-card:nth-child(4) {
    animation-delay: 0.3s;
}

.article-card:nth-child(5) {
    animation-delay: 0.4s;
}

.article-card:nth-child(6) {
    animation-delay: 0.5s;
}
</style>
@endsection