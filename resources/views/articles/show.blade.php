@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="fas fa-home"></i> Beranda
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('articles') }}" class="text-decoration-none">Artikel</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 30) }}</li>
        </ol>
    </nav>

    <!-- Article Header -->
    <div class="article-header mb-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="text-center mb-4">
                    <span class="badge bg-primary mb-3 px-3 py-2">
                        <i class="fas fa-tag me-1"></i>Artikel Kesehatan
                    </span>
                    <h1 class="article-title display-5 fw-bold text-dark mb-4">
                        {{ $article->title }}
                    </h1>
                    
                    <!-- Article Meta -->
                    <div class="article-meta d-flex flex-wrap justify-content-center align-items-center gap-3 mb-4">
                        <div class="meta-item">
                            <i class="fas fa-calendar-alt text-muted me-1"></i>
                            <span class="text-muted">{{ $article->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock text-muted me-1"></i>
                            <span class="text-muted">{{ $article->created_at->format('H:i') }} WIB</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-eye text-muted me-1"></i>
                            <span class="text-muted">{{ number_format(rand(50, 500)) }} views</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-book-open text-muted me-1"></i>
                            <span class="text-muted">{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} min read</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Featured Image -->
            @if($article->image)
                <div class="article-image mb-5">
                    <div class="image-container position-relative overflow-hidden rounded-4 shadow-lg">
                        <img src="{{ asset('storage/' . $article->image) }}" 
                             alt="{{ $article->title }}" 
                             class="img-fluid w-100 article-featured-image">
                        <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end">
                            <div class="image-caption p-3 text-white w-100">
                                <small class="opacity-75">{{ $article->title }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Article Body -->
            <div class="article-body">
                <div class="content-wrapper">
                    <div class="article-content">
                        {!! $article->content !!}
                    </div>
                </div>
            </div>

            <!-- Article Footer -->
            <div class="article-footer mt-5 pt-4 border-top">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="text-muted">Bagikan artikel:</span>
                            <div class="social-share d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm rounded-circle share-facebook" 
                                        data-url="{{ url()->current() }}"
                                        data-title="{{ $article->title }}"
                                        data-bs-toggle="tooltip" 
                                        title="Bagikan ke Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </button>
                                <button class="btn btn-outline-info btn-sm rounded-circle share-twitter" 
                                        data-url="{{ url()->current() }}"
                                        data-title="{{ $article->title }}"
                                        data-bs-toggle="tooltip" 
                                        title="Bagikan ke Twitter">
                                    <i class="fab fa-twitter"></i>
                                </button>
                                <button class="btn btn-outline-success btn-sm rounded-circle share-whatsapp" 
                                        data-url="{{ url()->current() }}"
                                        data-title="{{ $article->title }}"
                                        data-bs-toggle="tooltip" 
                                        title="Bagikan ke WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </button>
                                <button class="btn btn-outline-warning btn-sm rounded-circle share-telegram" 
                                        data-url="{{ url()->current() }}"
                                        data-title="{{ $article->title }}"
                                        data-bs-toggle="tooltip" 
                                        title="Bagikan ke Telegram">
                                    <i class="fab fa-telegram-plane"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-sm rounded-circle copy-link" 
                                        data-url="{{ url()->current() }}"
                                        data-bs-toggle="tooltip" 
                                        title="Salin Link">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('articles') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Artikel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Articles or CTA -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="cta-section bg-light rounded-4 p-5 text-center">
                <h3 class="mb-3">Butuh Konsultasi Kesehatan?</h3>
                <p class="text-muted mb-4">Tim apoteker kami siap membantu Anda dengan konsultasi gratis seputar kesehatan dan obat-obatan.</p>
                <a href="{{ route('consultation.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-user-md me-2"></i>Konsultasi Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="shareToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Berhasil!</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Link artikel berhasil disalin ke clipboard!
        </div>
    </div>
</div>

<style>
/* Article Specific Styles */
.article-title {
    line-height: 1.2;
    color: #2c3e50;
}

.article-meta .meta-item {
    font-size: 0.9rem;
}

.article-meta .meta-item:not(:last-child)::after {
    content: "•";
    margin-left: 1rem;
    color: #dee2e6;
}

.article-image .image-container {
    max-height: 400px;
    overflow: hidden;
}

.article-featured-image {
    height: 400px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.article-featured-image:hover {
    transform: scale(1.05);
}

.image-overlay {
    background: linear-gradient(transparent, rgba(0,0,0,0.7));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-container:hover .image-overlay {
    opacity: 1;
}

.article-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #2c3e50;
}

.article-content h1,
.article-content h2,
.article-content h3,
.article-content h4,
.article-content h5,
.article-content h6 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.article-content h2 {
    font-size: 1.8rem;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
}

.article-content h3 {
    font-size: 1.5rem;
    color: #3498db;
}

.article-content p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.article-content ul,
.article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.article-content li {
    margin-bottom: 0.5rem;
}

.article-content blockquote {
    border-left: 4px solid #3498db;
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    color: #555;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin: 1.5rem 0;
}

.article-content table {
    width: 100%;
    margin: 1.5rem 0;
    border-collapse: collapse;
}

.article-content table th,
.article-content table td {
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    text-align: left;
}

.article-content table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.social-share .btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    border: none;
}

.social-share .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.social-share .share-facebook:hover {
    background-color: #1877f2;
    color: white;
}

.social-share .share-twitter:hover {
    background-color: #1da1f2;
    color: white;
}

.social-share .share-whatsapp:hover {
    background-color: #25d366;
    color: white;
}

.social-share .share-telegram:hover {
    background-color: #0088cc;
    color: white;
}

.social-share .copy-link:hover {
    background-color: #6c757d;
    color: white;
}

.cta-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 1px solid #dee2e6;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #6c757d;
}

/* Loading animation for buttons */
.btn.loading {
    position: relative;
    color: transparent !important;
}

.btn.loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .article-title {
        font-size: 1.8rem !important;
    }
    
    .article-meta {
        flex-direction: column;
        gap: 0.5rem !important;
    }
    
    .article-meta .meta-item:not(:last-child)::after {
        display: none;
    }
    
    .article-content {
        font-size: 1rem;
        line-height: 1.7;
    }
    
    .social-share {
        justify-content: center;
        margin-top: 1rem;
    }
}

/* Print Styles */
@media print {
    .breadcrumb,
    .article-footer,
    .cta-section {
        display: none;
    }
    
    .article-content {
        font-size: 12pt;
        line-height: 1.6;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Social Share Functions
    const SocialShare = {
        // Facebook Share
        facebook: function(url, title) {
            const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
            this.openPopup(shareUrl, 'Facebook Share', 600, 400);
        },
        
        // Twitter Share
        twitter: function(url, title) {
            const shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title + ' - Apotik Gampil')}`;
            this.openPopup(shareUrl, 'Twitter Share', 600, 400);
        },
        
        // WhatsApp Share
        whatsapp: function(url, title) {
            const text = `${title}\n\nBaca selengkapnya: ${url}\n\n#ApotikGampil #KesehatanAnda`;
            const shareUrl = `https://wa.me/?text=${encodeURIComponent(text)}`;
            
            // Detect if mobile device
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                window.open(shareUrl, '_blank');
            } else {
                this.openPopup(shareUrl, 'WhatsApp Share', 600, 400);
            }
        },
        
        // Telegram Share
        telegram: function(url, title) {
            const text = `${title}\n\nBaca selengkapnya: ${url}`;
            const shareUrl = `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
            this.openPopup(shareUrl, 'Telegram Share', 600, 400);
        },
        
        // Copy Link
        copyLink: function(url) {
            return navigator.clipboard.writeText(url).then(() => {
                this.showToast('Link artikel berhasil disalin ke clipboard!', 'success');
                return true;
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                try {
                    document.execCommand('copy');
                    this.showToast('Link artikel berhasil disalin ke clipboard!', 'success');
                    return true;
                } catch (err) {
                    this.showToast('Gagal menyalin link. Silakan salin manual.', 'error');
                    return false;
                } finally {
                    document.body.removeChild(textArea);
                }
            });
        },
        
        // Open popup window
        openPopup: function(url, title, width, height) {
            const left = (screen.width / 2) - (width / 2);
            const top = (screen.height / 2) - (height / 2);
            const features = `width=${width},height=${height},left=${left},top=${top},scrollbars=yes,resizable=yes`;
            
            window.open(url, title, features);
        },
        
        // Show toast notification
        showToast: function(message, type = 'success') {
            const toastEl = document.getElementById('shareToast');
            const toastBody = toastEl.querySelector('.toast-body');
            const toastHeader = toastEl.querySelector('.toast-header');
            
            // Update message
            toastBody.textContent = message;
            
            // Update style based on type
            toastHeader.className = `toast-header ${type === 'success' ? 'bg-success' : 'bg-danger'} text-white`;
            
            // Show toast
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    };
    
    // Event Listeners for Social Share Buttons
    document.querySelectorAll('.share-facebook').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const title = this.dataset.title;
            
            this.classList.add('loading');
            setTimeout(() => {
                SocialShare.facebook(url, title);
                this.classList.remove('loading');
            }, 300);
        });
    });
    
    document.querySelectorAll('.share-twitter').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const title = this.dataset.title;
            
            this.classList.add('loading');
            setTimeout(() => {
                SocialShare.twitter(url, title);
                this.classList.remove('loading');
            }, 300);
        });
    });
    
    document.querySelectorAll('.share-whatsapp').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const title = this.dataset.title;
            
            this.classList.add('loading');
            setTimeout(() => {
                SocialShare.whatsapp(url, title);
                this.classList.remove('loading');
            }, 300);
        });
    });
    
    document.querySelectorAll('.share-telegram').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            const title = this.dataset.title;
            
            this.classList.add('loading');
            setTimeout(() => {
                SocialShare.telegram(url, title);
                this.classList.remove('loading');
            }, 300);
        });
    });
    
    document.querySelectorAll('.copy-link').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            
            this.classList.add('loading');
            SocialShare.copyLink(url).then(() => {
                this.classList.remove('loading');
                
                // Update tooltip text temporarily
                const tooltip = bootstrap.Tooltip.getInstance(this);
                const originalTitle = this.getAttribute('data-bs-original-title');
                this.setAttribute('data-bs-original-title', 'Berhasil disalin!');
                tooltip.show();
                
                setTimeout(() => {
                    this.setAttribute('data-bs-original-title', originalTitle);
                    tooltip.hide();
                }, 2000);
            });
        });
    });
    
    // Web Share API (for supported browsers)
    if (navigator.share) {
        // Add native share button if Web Share API is supported
        const shareContainer = document.querySelector('.social-share');
        const nativeShareBtn = document.createElement('button');
        nativeShareBtn.className = 'btn btn-outline-dark btn-sm rounded-circle';
        nativeShareBtn.innerHTML = '<i class="fas fa-share-alt"></i>';
        nativeShareBtn.setAttribute('data-bs-toggle', 'tooltip');
        nativeShareBtn.setAttribute('title', 'Bagikan');
        
        nativeShareBtn.addEventListener('click', async function() {
            const url = document.querySelector('.copy-link').dataset.url;
            const title = document.querySelector('.copy-link').dataset.title;
            
            try {
                await navigator.share({
                    title: title,
                    text: `Baca artikel menarik dari Apotik Gampil: ${title}`,
                    url: url
                });
            } catch (err) {
                console.log('Error sharing:', err);
            }
        });
        
        shareContainer.appendChild(nativeShareBtn);
        
        // Initialize tooltip for new button
        new bootstrap.Tooltip(nativeShareBtn);
    }
});
</script>
@endsection