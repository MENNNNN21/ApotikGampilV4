<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title') - Apotik Gampil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <style>
        /* Sidebar Styles */
        #wrapper {
            overflow-x: hidden;
        }

        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: -15rem;
            transition: margin 0.25s ease-out;
            width: 15rem;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        #page-content-wrapper {
            min-width: 0;
            width: 100%;
        }

        #wrapper.toggled #page-content-wrapper {
            margin-left: 15rem;
        }

        .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
            font-weight: bold;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .list-group-item {
            border: none;
            border-radius: 0;
            color: #495057;
            padding: 0.75rem 1.25rem;
        }

        .list-group-item:hover {
            background-color: #e9ecef;
            color: #007bff;
        }

        .list-group-item.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* Mobile Responsive */
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #page-content-wrapper {
                margin-left: 15rem;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -15rem;
            }

            #wrapper.toggled #page-content-wrapper {
                margin-left: 0;
            }
        }

        /* Navbar brand styles */
        .navbar-brand img {
            height: 30px;
            margin-right: 8px;
        }

        /* Content area */
        .main-content {
            min-height: calc(100vh - 56px);
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="bg-light border-end" id="sidebar-wrapper">
            <div class="sidebar-heading">
                <i class="fas fa-pills me-2"></i>
                Apotik Gampil Admin
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.dashboard') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.services.index') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-medical me-2"></i>
                    Layanan
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags me-2"></i>
                    Kategori
                </a>
                <a href="{{ route('admin.products.index') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-capsules me-2"></i>
                    Produk
                </a>
                <a href="{{ route('admin.articles.index') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper me-2"></i>
                    Artikel
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-grow-1">
            <!-- Top Navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-outline-primary me-3" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="navbar-brand d-lg-none">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="d-inline-block align-text-top">
                        Apotik Gampil
                    </div>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" 
                                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle fa-lg me-2"></i>
                                    <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-user me-2"></i>
                                            Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="fas fa-cog me-2"></i>
                                            Settings
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i>
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid p-4 main-content">
                <!-- Breadcrumb -->
                @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        @foreach($breadcrumbs as $breadcrumb)
                            @if($loop->last)
                                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['title'] }}</li>
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
                @endif

                <!-- Page Title -->
                @if(isset($pageTitle))
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">{{ $pageTitle }}</h1>
                    @yield('page-actions')
                </div>
                @endif

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Main Content Area -->
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Toggle
        document.getElementById("menu-toggle").addEventListener("click", function(e) {
            e.preventDefault();
            document.getElementById("wrapper").classList.toggle("toggled");
            
            // Save sidebar state to localStorage
            const isToggled = document.getElementById("wrapper").classList.contains("toggled");
            localStorage.setItem('sidebarToggled', isToggled);
        });

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggled = localStorage.getItem('sidebarToggled') === 'true';
            if (sidebarToggled) {
                document.getElementById("wrapper").classList.add("toggled");
            }
        });

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>