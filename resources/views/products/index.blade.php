@extends('layouts.app')

@section('title', 'Produk')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h2 class="display-4 fw-bold text-primary mb-3">Kategori Obat</h2>
        <p class="lead text-muted">Temukan obat yang Anda butuhkan berdasarkan kategori</p>
        <div class="mx-auto" style="width: 120px; height: 4px; background: linear-gradient(45deg, #28a745, #007bff); border-radius: 2px;"></div>
    </div>

    <!-- Categories Grid -->
    <div class="row g-4">
        @foreach($categories as $category)
        <div class="col-lg-4 col-md-6">
            <div class="category-card position-relative overflow-hidden">
                <a href="{{ route('products.category', $category->slug) }}" class="text-decoration-none h-100 d-block">
                    <!-- Background Pattern -->
                    <div class="category-bg position-absolute w-100 h-100"></div>
                    
                    <!-- Card Content -->
                    <div class="category-content position-relative h-100 p-4 d-flex flex-column justify-content-center text-center">
                        <!-- Icon Container -->
                        <div class="category-icon mb-4">
                            <div class="icon-circle mx-auto d-flex align-items-center justify-content-center">
                                <i class="fas fa-pills fa-2x text-white"></i>
                            </div>
                        </div>
                        
                        <!-- Category Name -->
                        <h4 class="category-title mb-3 fw-bold text-dark">{{ $category->name }}</h4>
                        
                        <!-- Description -->
                        <p class="category-description text-muted mb-4 flex-grow-1">Lihat semua obat dalam kategori ini</p>
                        
                        <!-- Action Button -->
                        <div class="category-action mt-auto">
                            <span class="btn btn-outline-primary btn-sm px-4 py-2 rounded-pill fw-medium">
                                Lihat Produk
                                <i class="fas fa-arrow-right ms-2"></i>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Hover Overlay -->
                    <div class="category-overlay position-absolute w-100 h-100 top-0 start-0"></div>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Additional Info Section -->
    <div class="text-center mt-5 pt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="info-card p-4 rounded-3 border-0" style="background: linear-gradient(135deg, #f8f9ff 0%, #e3f2fd 100%);">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Penting
                    </h5>
                    <p class="text-muted mb-0">
                        Konsultasikan dengan dokter atau apoteker sebelum menggunakan obat. 
                        Pastikan untuk membaca petunjuk penggunaan dengan teliti.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Category Card Styles */
.category-card {
    height: 280px;
    border-radius: 20px;
    background: #fff;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: 1px solid rgba(0,0,0,0.05);
    cursor: pointer;
}

.category-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

/* Background Pattern */
.category-bg {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f5e8 100%);
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.category-card:hover .category-bg {
    opacity: 1;
}

/* Icon Styles */
.icon-circle {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #28a745, #20c997);
    border-radius: 50%;
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
    transition: all 0.3s ease;
}

.category-card:hover .icon-circle {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 12px 25px rgba(40, 167, 69, 0.4);
}

/* Content Styles */
.category-content {
    z-index: 2;
}

.category-title {
    font-size: 1.4rem;
    transition: color 0.3s ease;
}

.category-card:hover .category-title {
    color: #28a745 !important;
}

.category-description {
    font-size: 0.95rem;
    line-height: 1.6;
}

/* Button Styles */
.category-action .btn {
    transition: all 0.3s ease;
    border-width: 2px;
}

.category-card:hover .category-action .btn {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
}

/* Overlay Effect */
.category-overlay {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(0, 123, 255, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 1;
}

.category-card:hover .category-overlay {
    opacity: 1;
}

/* Info Card */
.info-card {
    border: 1px solid rgba(0, 123, 255, 0.1);
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
}

/* Different Colors for Cards */
.category-card:nth-child(3n+1) .category-bg {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
}

.category-card:nth-child(3n+1) .icon-circle {
    background: linear-gradient(135deg, #2196f3, #9c27b0);
    box-shadow: 0 8px 20px rgba(33, 150, 243, 0.3);
}

.category-card:nth-child(3n+1):hover .category-title {
    color: #2196f3 !important;
}

.category-card:nth-child(3n+1):hover .category-action .btn {
    background-color: #2196f3;
    border-color: #2196f3;
    box-shadow: 0 6px 15px rgba(33, 150, 243, 0.3);
}

.category-card:nth-child(3n+2) .category-bg {
    background: linear-gradient(135deg, #fff3e0 0%, #fce4ec 100%);
}

.category-card:nth-child(3n+2) .icon-circle {
    background: linear-gradient(135deg, #ff9800, #e91e63);
    box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
}

.category-card:nth-child(3n+2):hover .category-title {
    color: #ff9800 !important;
}

.category-card:nth-child(3n+2):hover .category-action .btn {
    background-color: #ff9800;
    border-color: #ff9800;
    box-shadow: 0 6px 15px rgba(255, 152, 0, 0.3);
}

/* Responsive Design */
@media (max-width: 768px) {
    .category-card {
        height: 250px;
        margin-bottom: 1.5rem;
    }
    
    .category-card:hover {
        transform: translateY(-6px) scale(1.01);
    }
    
    .display-4 {
        font-size: 2rem;
    }
    
    .icon-circle {
        width: 70px;
        height: 70px;
    }
    
    .category-title {
        font-size: 1.2rem;
    }
}

/* Loading Animation */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.category-card {
    animation: slideInUp 0.6s ease forwards;
}

.category-card:nth-child(2) {
    animation-delay: 0.1s;
}

.category-card:nth-child(3) {
    animation-delay: 0.2s;
}

.category-card:nth-child(4) {
    animation-delay: 0.3s;
}

.category-card:nth-child(5) {
    animation-delay: 0.4s;
}

.category-card:nth-child(6) {
    animation-delay: 0.5s;
}

/* Icon Variations */
.category-card:nth-child(3n+1) .fas {
    content: '\f0f3'; /* stethoscope */
}

.category-card:nth-child(3n+2) .fas {
    content: '\f484'; /* prescription bottle */
}
</style>

<!-- Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection