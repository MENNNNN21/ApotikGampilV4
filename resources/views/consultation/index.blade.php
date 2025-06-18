@extends('layouts.app')

@section('title', 'Konsultasi')

@section('content')
<!-- Hero Section -->
<div class="consultation-hero py-5 mb-5" style="background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%); position: relative; overflow: hidden;">
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 0%, transparent 50%), radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 0%, transparent 50%);"></div>
    <div class="container text-center text-white position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="hero-icon mb-4">
                    <div class="bg-white bg-opacity-20 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                        <i class="fas fa-user-md text-white" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h1 class="display-4 fw-bold mb-3">Konsultasi Gratis</h1>
                <p class="lead fs-5 mb-4">Dapatkan saran kesehatan dari ahli farmasi berpengalaman</p>
                <div class="d-flex justify-content-center gap-4 flex-wrap">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>Konsultasi 24/7</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-shield-alt me-2"></i>
                        <span>Privasi Terjamin</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock me-2"></i>
                        <span>Respon Cepat</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <!-- Info Cards -->
            <div class="row mb-5">
                <div class="col-md-4 mb-3">
                    <div class="info-card text-center p-3 h-100 bg-white rounded-3 shadow-sm border-0">
                        <div class="info-icon bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="fab fa-whatsapp text-success fs-5"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Via WhatsApp</h6>
                        <small class="text-muted">Langsung terhubung</small>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="info-card text-center p-3 h-100 bg-white rounded-3 shadow-sm border-0">
                        <div class="info-icon bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="fas fa-user-shield text-primary fs-5"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Ahli Farmasi</h6>
                        <small class="text-muted">Berpengalaman</small>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="info-card text-center p-3 h-100 bg-white rounded-3 shadow-sm border-0">
                        <div class="info-icon bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                            <i class="fas fa-heart text-info fs-5"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Gratis</h6>
                        <small class="text-muted">Tanpa biaya</small>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="form-card bg-white rounded-4 shadow-lg border-0 overflow-hidden">
                <!-- Form Header -->
                <div class="form-header bg-gradient-primary text-white p-4 text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3 class="fw-bold mb-2">
                        <i class="fas fa-comments me-2"></i>
                        Form Konsultasi
                    </h3>
                    <p class="mb-0 opacity-90">Isi data Anda untuk memulai konsultasi</p>
                </div>

                <!-- Form Body -->
                <div class="form-body p-4">
                    <form action="{{ route('consultation.submit') }}" method="POST" id="consultationForm">
                        @csrf
                        
                        <!-- Progress Bar -->
                        <div class="progress mb-4" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 0%" id="formProgress"></div>
                        </div>

                        <!-- Name Field -->
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap" required>
                            <label for="name">
                                <i class="fas fa-user text-muted me-2"></i>Nama Lengkap
                            </label>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Phone Field -->
                        <div class="form-floating mb-4">
                            <input type="tel" class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="Nomor Telepon" required>
                            <label for="phone">
                                <i class="fas fa-phone text-muted me-2"></i>Nomor Telepon
                            </label>
                            @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Pastikan nomor WhatsApp aktif untuk mendapat respon
                                </small>
                            </div>
                        </div>
                        
                        <!-- Message Field -->
                        <div class="form-floating mb-4">
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" placeholder="Pesan" 
                                      style="height: 120px;" required>{{ old('message') }}</textarea>
                            <label for="message">
                                <i class="fas fa-comment-medical text-muted me-2"></i>Keluhan / Pertanyaan Anda
                            </label>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Jelaskan keluhan Anda sedetail mungkin untuk saran terbaik
                                </small>
                            </div>
                        </div>
                        
                        <!-- Privacy Notice -->
                        <div class="privacy-notice bg-light rounded-3 p-3 mb-4">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-shield-alt text-success me-2 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Privasi Terjamin</h6>
                                    <small class="text-muted">Semua informasi yang Anda berikan akan dijaga kerahasiaan dan hanya digunakan untuk keperluan konsultasi kesehatan.</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-success btn-lg w-100 py-3 rounded-3 fw-bold submit-btn" style="transition: all 0.3s ease;">
                            <div class="btn-content">
                                <i class="fab fa-whatsapp me-2 fs-5"></i>
                                <span>Mulai Konsultasi via WhatsApp</span>
                            </div>
                            <div class="btn-loading d-none">
                                <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                                <span>Mengirim...</span>
                            </div>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="additional-info mt-4 text-center">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center justify-content-center text-muted">
                            <i class="fas fa-clock me-2 text-primary"></i>
                            <small>Respon dalam 5-10 menit</small>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center justify-content-center text-muted">
                            <i class="fas fa-star me-2 text-warning"></i>
                            <small>Rating 4.9/5 dari pengguna</small>
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
    .info-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }
    
    .form-card {
        transition: all 0.3s ease;
    }
    
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: var(--bs-primary);
    }
    
    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(25, 135, 84, 0.3);
    }
    
    .submit-btn:active {
        transform: translateY(0);
    }
    
    .privacy-notice {
        border-left: 4px solid var(--bs-success);
    }
    
    /* Form Progress Animation */
    .progress-bar {
        transition: width 0.5s ease;
    }
    
    /* Floating Animation */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .hero-icon {
        animation: float 3s ease-in-out infinite;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2rem;
        }
        
        .form-header h3 {
            font-size: 1.5rem;
        }
        
        .info-card {
            margin-bottom: 1rem;
        }
    }
    
    /* Form Animation */
    .form-card {
        animation: slideInUp 0.6s ease;
    }
    
    @keyframes slideInUp {
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('consultationForm');
    const inputs = form.querySelectorAll('input, textarea');
    const progressBar = document.getElementById('formProgress');
    const submitBtn = form.querySelector('.submit-btn');
    
    // Update progress bar
    function updateProgress() {
        const filledInputs = Array.from(inputs).filter(input => input.value.trim() !== '');
        const progress = (filledInputs.length / inputs.length) * 100;
        progressBar.style.width = progress + '%';
    }
    
    // Add event listeners to inputs
    inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
    });
    
    // Form submission with loading state
    form.addEventListener('submit', function(e) {
        const btnContent = submitBtn.querySelector('.btn-content');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        btnContent.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        submitBtn.disabled = true;
    });
    
    // Initial progress update
    updateProgress();
});
</script>
@endpush