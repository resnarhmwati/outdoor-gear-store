<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Resn Outdoor Gear')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        .brand-link {
            background: #2d5f3f !important;
            color: white !important;
        }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background-color: #3d7a54 !important;
        }
        .btn-primary {
            background-color: #2d5f3f !important;
            border-color: #2d5f3f !important;
        }
        .btn-primary:hover {
            background-color: #3d7a54 !important;
            border-color: #3d7a54 !important;
        }
        .product-card {
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid #dee2e6;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-color: #2d5f3f;
        }
        .cart-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .cart-item {
            background: white;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
    </style>
    
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#" role="button">
                        <i class="fas fa-user-circle"></i> @auth
    <span>{{ Auth::user()->name }}</span>
@else
    <span>Guest</span>
@endauth

                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('orders.index') }}" class="brand-link">
                <i class="fas fa-mountain brand-image" style="opacity: .8; margin-left: 12px;"></i>
                <span class="brand-text font-weight-light">Outdoor Gear Store</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="{{ route('home.index') }}" class="nav-link {{ request()->routeIs('home.*') ? 'active' : '' }}">
                                <i class="bi bi-house-door nav-icon"></i>
                                    <p>Home</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cash-register"></i>
                                <p>POS / Kasir</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders.history') }}" class="nav-link {{ request()->routeIs('orders.history') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Riwayat Pesanan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('orders.report') }}" class="nav-link {{ request()->routeIs('orders.report') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Laporan Harian</p>
                            </a>
                        </li>
                        <li class="nav-header">MASTER DATA</li>
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tags"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Customer</p>
                            </a>
                        </li>
                        <!-- Kelola Akun -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('profile') }}" role="button">
            <i class="fas fa-user-cog"></i> Kelola Akun
        </a>
    </li>
                        <!-- Form untuk Logout -->
<form method="POST" action="{{ route('logout') }}" style="display: inline;">
    @csrf
    <button type="submit" class="nav-link btn btn-link" style="color: white;">
        <i class="fas fa-sign-out-alt"></i> Logout
    </button>
</form>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 Outdoor Gear POS.</strong>
            All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    
    @stack('scripts')
</body>
</html>
