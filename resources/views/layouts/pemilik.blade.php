<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dasbor Pemilik') - SiKandang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f5f7; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: 250px; min-height: 100vh; background-color: #162238; color: #fff;
            position: fixed; top: 0; left: 0; padding-top: 24px; z-index: 100;
        }
        .sidebar .brand { font-size: 1.4rem; font-weight: 700; padding: 0 24px 24px; color: #fff; text-decoration: none; display: block; }
        .sidebar .nav-link {
            color: #cfd8e3; padding: 12px 24px; margin: 4px 12px; border-radius: 6px;
            font-weight: 500; display: flex; align-items: center; gap: 10px;
        }
        .sidebar .nav-link:hover { background-color: rgba(255,255,255,0.08); color: #fff; }
        .sidebar .nav-link.active { background-color: #d92525; color: #fff; font-weight: 600; }
        .main-wrapper { margin-left: 250px; min-height: 100vh; display: flex; flex-direction: column; }
        .content-area { padding: 28px 32px; flex: 1; }
        .bottom-bar { height: 22px; background-color: #d92525; width: 100%; }
        @media print {
            .sidebar, .no-print, .bottom-bar { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
        }
    </style>
</head>
<body>

    <!-- Sidebar Kiri -->
    <aside class="sidebar">
        <a href="{{ route('dashboard') }}" class="brand">SiKandang</a>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dasbor
            </a>
            <a class="nav-link {{ request()->routeIs('rekomendasi.*') ? 'active' : '' }}" href="{{ route('rekomendasi.index') }}">
                <i class="bi bi-lightbulb"></i> Rekomendasi
            </a>
            <a class="nav-link {{ request()->routeIs('catatan.*') ? 'active' : '' }}" href="{{ route('catatan.index') }}">
                <i class="bi bi-journal-text"></i> Catatan Harian
            </a>
            <a class="nav-link {{ request()->routeIs('pakan.*') ? 'active' : '' }}" href="{{ route('pakan.index') }}">
                <i class="bi bi-box-seam"></i> Stok Pakan
            </a>
            <a class="nav-link {{ request()->routeIs('aturan.*') ? 'active' : '' }}" href="{{ route('aturan.index') }}">
                <i class="bi bi-sliders"></i> Aturan Rekomendasi
            </a>
            <a class="nav-link {{ request()->routeIs('produk.*') ? 'active' : '' }}" href="{{ route('produk.index') }}">
                <i class="bi bi-tags"></i> Produk dan Harga
            </a>
            <a class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}" href="{{ route('pesanan.index') }}">
                <i class="bi bi-cart-check"></i> Pesanan
            </a>
            <a class="nav-link" href="{{ route('beranda') }}" target="_blank">
                <i class="bi bi-shop"></i> Lihat Halaman Pembeli
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                    <i class="bi bi-box-arrow-left"></i> Keluar
                </button>
            </form>
        </nav>
    </aside>

    <!-- Area Konten Kanan -->
    <div class="main-wrapper">
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show no-print">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show no-print">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
        <div class="bottom-bar"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>