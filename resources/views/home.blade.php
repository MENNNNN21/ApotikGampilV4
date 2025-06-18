@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Section dengan Modern Design -->
<div class="hero-section position-relative overflow-hidden bg-white">
    <div class="container">
        <div class="row align-items-center min-vh-80 py-5">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="hero-content">
                    <!-- Logo Integration -->
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ asset('img/apotikgampil.png') }}" 
                             alt="Apotik Gampil Logo" 
                             class="logo-hero me-3"
                             style="height: 60px; width: auto;">
                        <div>
                            <h1 class="display-5 fw-bold text-primary mb-0">APOTIK</h1>
                            <h2 class="h4 fw-semibold text-success mb-0">GAMPIL</h2>
                        </div>
                    </div>
                    
                    <p class="lead text-muted mb-4">
                        Solusi kesehatan keluarga Anda dengan pelayanan profesional, 
                        obat berkualitas, dan konsultasi ahli yang terpercaya sejak 2020.
                    </p>
                    
                    <!-- Trust Indicators -->
                    <div class="trust-indicators mb-4">
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="badge-icon bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                    <small class="fw-semibold text-black">Terpercaya</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="badge-icon bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="fas fa-pills text-primary"></i>
                                    </div>
                                    <small class="fw-semibold text-black">Obat Lengkap</small>
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="badge-icon bg-warning bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="fas fa-clock text-warning"></i>
                                    </div>
                                    <small class="fw-semibold text-black">24/7 Siaga</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('consultation.index') }}" class="btn btn-primary btn-lg px-4 py-3 rounded-pill shadow-sm">
                            <i class="fas fa-stethoscope me-2"></i>
                            Konsultasi Gratis
                        </a>
                        <a href="#layanan" class="btn btn-outline-primary btn-lg px-4 py-3 rounded-pill">
                            <i class="fas fa-info-circle me-2"></i>
                            Lihat Layanan
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 order-1 order-lg-2 mb-5 mb-lg-0">
                <div class="hero-visual text-center position-relative">
                    <!-- Main Logo Display -->
                    <div class="logo-container">
                        <div class="logo-backdrop"></div>
                        <img src="{{ asset('img/apotikgampil.png') }}" 
                             alt="Apotik Gampil" 
                             class="main-logo img-fluid">
                    </div>
                    
                    <!-- Floating Elements -->
                    <div class="floating-elements">
                        <div class="floating-card card-1">
                            <div class="card-body text-center p-3">
                                <div class="icon-wrapper bg-success rounded-circle mx-auto mb-2">
                                    <i class="fas fa-prescription-bottle-alt text-white"></i>
                                </div>
                                <h6 class="mb-1 fw-bold">1000+</h6>
                                <small class="text-muted">Jenis Obat</small>
                            </div>
                        </div>
                        
                        <div class="floating-card card-2">
                            <div class="card-body text-center p-3">
                                <div class="icon-wrapper bg-primary rounded-circle mx-auto mb-2">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <h6 class="mb-1 fw-bold">5000+</h6>
                                <small class="text-muted">Pelanggan</small>
                            </div>
                        </div>
                        
                        <div class="floating-card card-3">
                            <div class="card-body text-center p-3">
                                <div class="icon-wrapper bg-warning rounded-circle mx-auto mb-2">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                                <h6 class="mb-1 fw-bold">4.9</h6>
                                <small class="text-muted">Rating</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div id="layanan" class="py-5 bg-gradient-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold text-primary mb-3">Layanan Unggulan Kami</h2>
            <p class="lead text-muted">Komitmen kami untuk memberikan pelayanan kesehatan terbaik</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6 col-md-6">
                <div class="service-card text-center h-100">
                    <div class="service-icon bg-primary bg-gradient rounded-circle mx-auto mb-4">
                        <i class="fas fa-user-md text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3 text-primary">Konsultasi Ahli</h5>
                    <p class="text-muted mb-4">Dapatkan saran medis dari apoteker berpengalaman dengan sertifikasi resmi untuk kesehatan optimal Anda.</p>
                    <a href="{{ route('consultation.index') }}" class="btn btn-outline-primary rounded-pill service-btn">
                        Konsultasi Sekarang <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-6">
                <div class="service-card text-center h-100">
                    <div class="service-icon bg-success bg-gradient rounded-circle mx-auto mb-4">
                        <i class="fas fa-certificate text-white"></i>
                    </div>
                    <h5 class="fw-bold mb-3 text-success">Obat Original</h5>
                    <p class="text-muted mb-4">Semua produk dijamin original dan berkualitas tinggi langsung dari distributor resmi dengan sertifikat BPOM.</p>
                    <!-- Perbaikan href dengan beberapa opsi -->
                    <a href="{{ url('/products') }}" class="btn btn-outline-success rounded-pill service-btn">
                        Lihat Produk <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-4">
                <div class="stat-item">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users fa-3x opacity-75"></i>
                    </div>
                    <h3 class="fw-bold mb-1">5000+</h3>
                    <p class="mb-0 opacity-90">Pelanggan Puas</p>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-item">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-clock fa-3x opacity-75"></i>
                    </div>
                    <h3 class="fw-bold mb-1">24/7</h3>
                    <p class="mb-0 opacity-90">Layanan Siaga</p>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-item">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-star fa-3x opacity-75"></i>
                    </div>
                    <h3 class="fw-bold mb-1">4.9</h3>
                    <p class="mb-0 opacity-90">Rating Bintang</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="py-5 bg-gradient-cta">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="cta-content">
                    <h2 class="display-6 fw-bold mb-4 text-primary">Siap Membantu Kesehatan Anda?</h2>
                    <p class="lead mb-4 text-muted">
                        Tim ahli kami siap memberikan konsultasi dan solusi terbaik untuk kebutuhan kesehatan Anda dan keluarga.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('consultation.index') }}" class="btn btn-primary btn-lg px-4 py-3 rounded-pill shadow-sm">
                            <i class="fas fa-comments me-2"></i>
                            Mulai Konsultasi
                        </a>
                        <a href="tel:+62123456789" class="btn btn-outline-primary btn-lg px-4 py-3 rounded-pill">
                            <i class="fas fa-phone me-2"></i>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Hero Section Styles */
.hero-section {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 50%;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="%23e3f2fd" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)" opacity="0.3"/></svg>');
    opacity: 0.1;
    pointer-events: none;
}

.min-vh-80 {
    min-height: 80vh;
}

.logo-hero {
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
}

/* Hero Visual Styles */
.hero-visual {
    position: relative;
}

.logo-container {
    position: relative;
    display: inline-block;
}

.logo-backdrop {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    opacity: 0.1;
    z-index: 1;
}

.main-logo {
    position: relative;
    z-index: 2;
    max-height: 250px;
    filter: drop-shadow(0 10px 30px rgba(0,0,0,0.1));
}

/* Floating Cards */
.floating-elements {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
}

.floating-card {
    position: absolute;
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,0,0,0.05);
    min-width: 120px;
    animation: float 4s ease-in-out infinite;
}

.floating-card .icon-wrapper {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-1 {
    top: 20%;
    left: -10%;
    animation-delay: 0s;
}

.card-2 {
    top: 60%;
    right: -10%;
    animation-delay: 1s;
}

.card-3 {
    bottom: 20%;
    left: 10%;
    animation-delay: 2s;
}

/* Service Cards */
.service-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    z-index: 1; /* Pastikan card tidak menutupi button */
}

.service-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, transparent 0%, rgba(0,0,0,0.02) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1; /* Pastikan overlay tidak menutupi konten */
}

.service-card:hover::before {
    opacity: 1;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.service-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

/* Background Gradients */
.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.bg-gradient-cta {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
}

/* Animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.trust-indicators .badge-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Button Enhancements */
.btn {
    transition: all 0.3s ease;
    border-width: 2px;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* Statistics */
.stat-item {
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: scale(1.05);
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-section {
        min-height: 60vh;
    }
    
    .floating-card {
        position: relative !important;
        margin: 10px auto;
        display: block;
        animation: none !important;
    }
    
    .logo-backdrop {
        width: 200px;
        height: 200px;
    }
    
    .main-logo {
        max-height: 180px;
    }
    
    .service-card {
        padding: 1.5rem;
    }
    
    .service-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .logo-hero {
        height: 40px !important;
    }
    
    .display-5 {
        font-size: 1.8rem;
    }
    
    .h4 {
        font-size: 1.2rem;
    }
}

/* Loading Animation */
.hero-content {
    animation: fadeInLeft 1s ease-out;
}

.hero-visual {
    animation: fadeInRight 1s ease-out;
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}
</style>
@endsection