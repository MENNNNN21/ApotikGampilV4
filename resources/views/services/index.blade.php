@extends('layouts.app')

@section('title', 'Layanan')

@section('content')
<div class="container-fluid py-5 bg-light">
    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary mb-3">Layanan Profesional Kami</h1>
        <p class="lead text-muted">Solusi terbaik untuk kebutuhan bisnis Anda</p>
        <div class="d-flex justify-content-center">
            <div class="bg-primary" style="width: 100px; height: 4px; border-radius: 2px;"></div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="container">
        <div class="row g-4">
            @foreach($services as $index => $service)
            <div class="col-lg-4 col-md-6">
                <div class="card service-card border-0 shadow-sm h-100 position-relative overflow-hidden" 
                     style="transition: all 0.3s ease; border-radius: 15px;">
                    
                    <!-- Service Number Badge -->
                    <div class="position-absolute top-0 start-0 m-3 z-index-1">
                        <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                    
                    <!-- Image Container -->
                    <div class="position-relative overflow-hidden" style="height: 250px;">
                        <img src="{{ asset('storage/' . $service->image) }}" 
                             class="card-img-top w-100 h-100 object-fit-cover" 
                             alt="{{ $service->name }}"
                             style="transition: transform 0.3s ease;">
                        <div class="position-absolute bottom-0 start-0 w-100 h-100 bg-gradient-to-top from-dark to-transparent opacity-75"></div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-3">
                            <h4 class="card-title fw-bold text-dark mb-2">{{ $service->name }}</h4>
                            <p class="card-text text-muted lh-base">{{ $service->description }}</p>
                        </div>
                        
                        <!-- Action Button -->
                        <div class="mt-auto">
                            <a href="{{ route('services.whatsapp', $service->id) }}" 
                               class="btn btn-success btn-lg w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold"
                               style="border-radius: 10px; transition: all 0.3s ease;">
                                <i class="fab fa-whatsapp fs-5"></i>
                                <span>Konsultasi Gratis</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Hover Effect Overlay -->
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-primary opacity-0 d-flex align-items-center justify-content-center"
                         style="transition: opacity 0.3s ease; pointer-events: none;">
                        <i class="fas fa-arrow-right text-white fs-1"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Call to Action Section -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="bg-white p-5 rounded-3 shadow-sm">
                    <h3 class="fw-bold text-dark mb-3">Butuh Konsultasi Khusus?</h3>
                    <p class="text-muted mb-4">Tim ahli kami siap membantu Anda menemukan solusi yang tepat untuk kebutuhan bisnis Anda.</p>
                    <a href="contact" class="btn btn-outline-primary btn-lg px-4 py-2">
                        <i class="fas fa-phone-alt me-2"></i>
                        Hubungi Kami Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
}

.service-card:hover img {
    transform: scale(1.05);
}

.service-card:hover .position-absolute.bg-primary {
    opacity: 0.1;
}

.btn-success:hover {
    background-color: #198754;
    border-color: #198754;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(25, 135, 84, 0.3);
}

.bg-gradient-to-top {
    background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
}

.object-fit-cover {
    object-fit: cover;
}

.z-index-1 {
    z-index: 1;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .service-card {
        margin-bottom: 2rem;
    }
}
</style>
@endsection