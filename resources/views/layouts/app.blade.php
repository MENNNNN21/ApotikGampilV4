<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Apotik Gampil</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Custom Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, #f0f0f1 0%, #fefdff 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.15);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #c00f0f !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
            border-radius: 8px;
        }
        
        .navbar-nav .nav-link {
            color: rgba(0, 0, 0, 0.9) !important;
            font-weight: 500;
            padding: 0.8rem 1.2rem !important;
            margin: 0 0.2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .navbar-nav .nav-link:hover::before {
            left: 100%;
        }
        
        .navbar-nav .nav-link:hover {
            color: #000000 !important;
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        
        .navbar-nav .nav-link.active {
            color: #fff !important;
            background: rgba(255,255,255,0.2);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        /* Profile Dropdown Styles */
        .profile-dropdown {
            position: relative;
        }
        
        .profile-toggle {
            background: rgba(255,255,255,0.1);
            border: 2px solid rgba(192, 15, 15, 0.2);
            border-radius: 50px;
            padding: 0.5rem 1rem;
            color: #c00f0f !important;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .profile-toggle:hover {
            background: rgba(192, 15, 15, 0.1);
            border-color: rgba(192, 15, 15, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(192, 15, 15, 0.2);
            color: #c00f0f !important;
        }
        
        .profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #c00f0f, #ff4757);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-radius: 15px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
        }
        
        .dropdown-item {
            padding: 0.7rem 1.5rem;
            transition: all 0.3s ease;
            color: #333;
            border-radius: 0;
        }
        
        .dropdown-item:hover {
            background: rgba(192, 15, 15, 0.1);
            color: #c00f0f;
            transform: translateX(5px);
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            border-color: rgba(0,0,0,0.1);
        }
        
        .navbar-toggler {
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .navbar-toggler:hover {
            border-color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.1);
        }
        
       
        
        /* Mobile responsive */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                margin-top: 1rem;
                background: rgba(255, 255, 255, 0.1);
                border-radius: 15px;
                padding: 1rem;
                backdrop-filter: blur(10px);
                
            }
            
            .navbar-nav .nav-link {
                margin: 0.2rem 0;
                text-align: center;
            }
            
            .profile-dropdown {
                margin-top: 1rem;
                text-align: center;
            }
            
            .profile-toggle {
                justify-content: center;
                width: fit-content;
                margin: 0 auto;
            }
        }
        
        /* Add some animation to the brand logo */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-3px); }
        }
        
        .navbar-brand img {
            animation: float 3s ease-in-out infinite;
        }
        
        /* Hover effect for navigation items */
        .nav-item {
            position: relative;
        }
        
        .nav-item::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #fff;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-item:hover::after {
            width: 80%;
        }
        
        /* User welcome text animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .user-welcome {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span>Apotik Gampil</span>
            </a>
            <button class="navbar-toggler " type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services') }}">
                            <i class="fas fa-stethoscope me-1"></i>Layanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products') }}">
                            <i class="fas fa-pills me-1"></i>Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('articles') }}">
                            <i class="fas fa-newspaper me-1"></i>Artikel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('consultation.index') }}">
                            <i class="fas fa-user-md me-1"></i>Konsultasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">
                            <i class="fas fa-phone me-1"></i>Kontak
                        </a>
                    </li>
                </ul>
                
                <!-- Profile Section -->
                <div class="navbar-nav">
                    @auth
                        <div class="nav-item dropdown profile-dropdown">
                            <a class="profile-toggle dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="profile-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <span class="user-welcome">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-user me-2"></i>Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-cog me-2"></i>Edit Profil
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                        </div>
                        <div class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4 mt-5">
        @yield('content')
    </main>

     <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Apotik Gampil</h5>
                    <p>Melayani dengan sepenuh hati untuk kesehatan Anda</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-light">Profil</a></li>
                        <li><a href="{{ route('services') }}" class="text-light">Layanan</a></li>
                        <li><a href="{{ route('products') }}" class="text-light">Produk</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone"></i> +62 822-6138-5228</li>
                        <li><i class="fas fa-envelope"></i> info@apotikgampil.com</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Add scroll effect to navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.profile-dropdown');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (dropdown && !dropdown.contains(event.target)) {
                const dropdownToggle = dropdown.querySelector('.dropdown-toggle');
                if (dropdownToggle && dropdownToggle.getAttribute('aria-expanded') === 'true') {
                    dropdownToggle.click();
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>