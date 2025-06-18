@extends('layouts.app')

@section('title', 'Kontak Kami')

@section('content')
<div class="contact-hero py-5 mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); position: relative; overflow: hidden;">
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
       <div style="
    background-image: url('data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'20\' cy=\'20\' r=\'2\' fill=\'white\'/><circle cx=\'80\' cy=\'20\' r=\'2\' fill=\'white\'/><circle cx=\'50\' cy=\'80\' r=\'2\' fill=\'white\'/></svg>');
    background-size: 50px 50px;
    width: 100%;
    height: 200px;
">
</div>

    </div>
    <div class="container text-center text-white position-relative">
        <h1 class="display-4 fw-bold mb-3">Hubungi Kami</h1>
        <p class="lead fs-5">Kami siap membantu Anda 24/7</p>
        <div class="d-flex justify-content-center mt-4">
            <div class="bg-white bg-opacity-25 rounded-pill px-4 py-2">
                <i class="fas fa-phone-alt me-2"></i>
                <span>Respon Cepat & Profesional</span>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-5">
        <!-- Contact Information -->
        <div class="col-lg-5">
            <div class="contact-info-wrapper">
                <div class="mb-4">
                    <h2 class="fw-bold text-dark mb-3">Informasi Kontak</h2>
                    <p class="text-muted">Jangan ragu untuk menghubungi kami melalui berbagai cara berikut</p>
                </div>

                <div class="contact-item-grid">
                    <!-- Address -->
                    <div class="contact-item bg-white rounded-4 p-4 mb-4 border-0 shadow-sm position-relative overflow-hidden">
                        <div class="contact-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-map-marker-alt text-primary fs-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Alamat Kami</h5>
                        <p class="text-muted mb-0 lh-base">
                            Jl. Sadang Tengah IV No.3, Sekeloa<br>
                            Kecamatan Coblong<br>
                            Kota Bandung, Jawa Barat 
                            40134
                        </p>
                        <div class="position-absolute top-0 end-0 opacity-5">
                            <i class="fas fa-map-marker-alt" style="font-size: 4rem; color: var(--bs-primary);"></i>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="contact-item bg-white rounded-4 p-4 mb-4 border-0 shadow-sm position-relative overflow-hidden">
                        <div class="contact-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-phone text-success fs-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Telepon</h5>
                        <div class="d-flex flex-column gap-2">
                            <a href="tel:+6281234567890" class="text-decoration-none text-muted hover-primary">
                                <i class="fas fa-phone-alt me-2"></i>+62 822-6138-5228
                            </a>
                        </div>
                        <div class="position-absolute top-0 end-0 opacity-5">
                            <i class="fas fa-phone" style="font-size: 4rem; color: var(--bs-success);"></i>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="contact-item bg-white rounded-4 p-4 mb-4 border-0 shadow-sm position-relative overflow-hidden">
                        <div class="contact-icon bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-envelope text-info fs-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Email</h5>
                        <div class="d-flex flex-column gap-2">
                            <a href="mailto:info@apotikgampil.com" class="text-decoration-none text-muted hover-primary">
                                <i class="fas fa-envelope me-2"></i>info@apotikgampil.com
                            </a>
                            <a href="mailto:support@apotikgampil.com" class="text-decoration-none text-muted hover-primary">
                                <i class="fas fa-headset me-2"></i>support@apotikgampil.com
                            </a>
                        </div>
                        <div class="position-absolute top-0 end-0 opacity-5">
                            <i class="fas fa-envelope" style="font-size: 4rem; color: var(--bs-info);"></i>
                        </div>
                    </div>

                    <!-- Operating Hours -->
                    <div class="contact-item bg-white rounded-4 p-4 mb-4 border-0 shadow-sm position-relative overflow-hidden">
                        <div class="contact-icon bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-clock text-warning fs-4"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">Jam Operasional</h5>
                        <div class="operating-hours">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Senin - Sabtu</span>
                                <span class="fw-semibold text-dark">08:00 - 21:00</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Minggu</span>
                                <span class="fw-semibold text-dark">09:00 - 20:00</span>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 opacity-5">
                            <i class="fas fa-clock" style="font-size: 4rem; color: var(--bs-warning);"></i>
                        </div>
                    </div>
                </div>

                <!-- Quick Contact Buttons -->
                <div class="quick-contact mt-4">
                    <h5 class="fw-bold text-dark mb-3">Kontak Cepat</h5>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="https://wa.me/6282261385228" class="btn btn-success btn-lg rounded-pill px-4 py-2 d-flex align-items-center gap-2 flex-fill justify-content-center">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="mailto:info@apotikgampil.com" class="btn btn-primary btn-lg rounded-pill px-4 py-2 d-flex align-items-center gap-2 flex-fill justify-content-center">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="col-lg-7">
            <div class="map-container bg-white rounded-4 overflow-hidden shadow-sm">
                <div class="map-header bg-light p-4 border-bottom">
                    <h4 class="fw-bold text-dark mb-2">
                        <i class="fas fa-map-marked-alt text-primary me-2"></i>
                        Lokasi Kami
                    </h4>
                    <p class="text-muted mb-0">Kunjungi toko fisik kami untuk pelayanan langsung</p>
                </div>
                <div class="map-wrapper position-relative">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.0131556593974!2d107.6230957750435!3d-6.8890269931100265!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e727570bc5cb%3A0x39f3fbfc9b6edec5!2sApotik%20Gampil!5e0!3m2!1sid!2sid!4v1750040070016!5m2!1sid!2sid"
                        width="100%" 
                        height="500"
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                    <div class="position-absolute top-0 start-0 p-3">
                        <div class="bg-white rounded-3 px-3 py-2 shadow-sm">
                            <small class="text-muted">
                                <i class="fas fa-location-arrow me-1"></i>
                                Lihat di Google Maps
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .contact-item {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .contact-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    
    .hover-primary:hover {
        color: var(--bs-primary) !important;
        text-decoration: underline !important;
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    .btn-success:hover {
        box-shadow: 0 8px 25px rgba(25, 135, 84, 0.3);
    }
    
    .btn-primary:hover {
        box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
    }
    
    .map-container {
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .operating-hours {
        background: rgba(255, 193, 7, 0.1);
        border-radius: 8px;
        padding: 12px;
    }
    
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2.5rem;
        }
        
        .contact-item {
            margin-bottom: 1.5rem;
        }
        
        .quick-contact .btn {
            font-size: 0.9rem;
        }
    }
    
    /* Animation for contact items */
    .contact-item {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    .contact-item:nth-child(1) { animation-delay: 0.1s; }
    .contact-item:nth-child(2) { animation-delay: 0.2s; }
    .contact-item:nth-child(3) { animation-delay: 0.3s; }
    .contact-item:nth-child(4) { animation-delay: 0.4s; }
    
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
</style>
@endpush