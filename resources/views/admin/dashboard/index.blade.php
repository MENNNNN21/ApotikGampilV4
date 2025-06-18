@extends('admin/layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800 font-weight-bold">Dashboard</h1>
            <p class="text-muted mb-0">Selamat datang di panel admin sistem manajemen apotek</p>
        </div>
        <div class="d-none d-sm-inline-block">
            <span class="badge badge-primary px-3 py-2">
                <i class="fas fa-calendar-alt mr-1"></i>
                {{ date('d F Y') }}
            </span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Articles Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-2 letter-spacing">
                                Total Artikel
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($artikelCount) }}
                            </div>
                            <div class="text-xs text-success mt-1">
                                <i class="fas fa-arrow-up mr-1"></i>
                                Aktif
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-primary">
                                <i class="fas fa-newspaper text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary bg-gradient text-white py-2">
                    <div class="d-flex align-items-center justify-content-between small">
                        <span>Lihat Detail</span>
                        <i class="fas fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kategori Obat Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-2 letter-spacing">
                                Kategori Obat
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($kategoriCount) }}
                            </div>
                            <div class="text-xs text-success mt-1">
                                <i class="fas fa-check-circle mr-1"></i>
                                Tersedia
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-success">
                                <i class="fas fa-list text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-success bg-gradient text-white py-2">
                    <div class="d-flex align-items-center justify-content-between small">
                        <span>Kelola Kategori</span>
                        <i class="fas fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Obat Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-2 letter-spacing">
                                Total Obat
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($obatCount) }}
                            </div>
                            <div class="text-xs text-info mt-1">
                                <i class="fas fa-boxes mr-1"></i>
                                Stok Tersedia
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-info">
                                <i class="fas fa-pills text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-info bg-gradient text-white py-2">
                    <div class="d-flex align-items-center justify-content-between small">
                        <span>Kelola Obat</span>
                        <i class="fas fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-2 letter-spacing">
                                Total Layanan
                            </div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($serviceCount) }}
                            </div>
                            <div class="text-xs text-warning mt-1">
                                <i class="fas fa-star mr-1"></i>
                                Layanan Aktif
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="icon-circle bg-warning">
                                <i class="fas fa-hands-helping text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-warning bg-gradient text-white py-2">
                    <div class="d-flex align-items-center justify-content-between small">
                        <span>Kelola Layanan</span>
                        <i class="fas fa-angle-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities Section -->
    <div class="row">
        <!-- Latest Articles -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-newspaper mr-2"></i>
                            Artikel Terbaru
                        </h6>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3">Judul</th>
                                    <th class="border-0 px-4 py-3">Tanggal</th>
                                    <th class="border-0 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestArticles as $article)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-medium">
                                            {{ Str::limit($article->title, 35) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge badge-light">
                                            {{ $article->created_at->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.articles.edit', $article->id) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               data-toggle="tooltip" 
                                               title="Edit Artikel">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-toggle="tooltip" 
                                                    title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 text-light"></i>
                                        <br>
                                        Belum ada artikel yang dibuat
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Products -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-pills mr-2"></i>
                            Obat Terbaru
                        </h6>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3">Nama</th>
                                    <th class="border-0 px-4 py-3">Kategori</th>
                                    <th class="border-0 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestProducts as $product)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-medium">
                                            {{ Str::limit($product->name, 30) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge badge-info">
                                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.products.edit', $product->id) }}" 
                                               class="btn btn-sm btn-outline-warning"
                                               data-toggle="tooltip" 
                                               title="Edit Obat">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-info"
                                                    data-toggle="tooltip" 
                                                    title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="fas fa-pills fa-3x mb-3 text-light"></i>
                                        <br>
                                        Belum ada obat yang ditambahkan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Services -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-hands-helping mr-2"></i>
                            Layanan Terbaru
                        </h6>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            Kelola Layanan
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="servicesTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 px-4 py-3">Nama Layanan</th>
                                    <th class="border-0 px-4 py-3">Deskripsi</th>
                                    <th class="border-0 px-4 py-3">Tanggal Dibuat</th>
                                    <th class="border-0 px-4 py-3 text-center">Status</th>
                                    <th class="border-0 px-4 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestServices as $service)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-weight-medium text-dark">
                                            {{ $service->name }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-muted">
                                            {{ Str::limit($service->description, 60) }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="badge badge-light">
                                            {{ $service->created_at->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Aktif
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.services.edit', $service->id) }}" 
                                               class="btn btn-sm btn-outline-warning"
                                               data-toggle="tooltip" 
                                               title="Edit Layanan">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm btn-outline-info"
                                                    data-toggle="tooltip" 
                                                    title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                    data-toggle="tooltip" 
                                                    title="Pengaturan">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-hands-helping fa-3x mb-3 text-light"></i>
                                        <br>
                                        Belum ada layanan yang tersedia
                                        <br>
                                        <a href="#" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus mr-1"></i>
                                            Tambah Layanan
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
}

.icon-circle {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.letter-spacing {
    letter-spacing: 0.5px;
}

.bg-gradient {
    background: linear-gradient(45deg, var(--bs-primary), var(--bs-primary-dark)) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.08);
}

.btn-group .btn {
    margin: 0 1px;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 1px 0;
        border-radius: 0.25rem !important;
    }
}

.card-footer {
    cursor: pointer;
    transition: all 0.2s ease;
}

.card-footer:hover {
    background-color: var(--bs-primary-dark) !important;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables with better configuration
    $('#servicesTable').DataTable({
        "pageLength": 5,
        "ordering": true,
        "searching": true,
        "lengthChange": false,
        "info": false,
        "language": {
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            },
            "emptyTable": "Tidak ada data yang tersedia"
        },
        "dom": '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"ml-auto"f>>rtip'
    });

    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Add click handlers for card footers
    $('.card-footer').click(function() {
        // Add navigation logic here
        console.log('Card footer clicked');
    });

    // Smooth animations
    $('.card').each(function(index) {
        $(this).css({
            'opacity': '0',
            'transform': 'translateY(20px)'
        }).delay(index * 100).animate({
            'opacity': '1'
        }, 500).css('transform', 'translateY(0px)');
    });
});
</script>
@endpush