<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Prima Medika - @yield('title')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --sidebar-bg: #ffffff;
            --sidebar-hover: #f3f4f6;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --background: #f9fafb;
            --card-bg: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--background);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--text-primary);
            font-size: 14px;
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            min-height: 100vh;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            width: 260px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
        }

        .sidebar-logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .sidebar-logo-text h4 {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
            color: var(--text-primary);
        }

        .sidebar-logo-text small {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .sidebar-nav {
            padding: 16px 12px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            padding: 8px 12px;
            margin-top: 16px;
        }

        .nav-section-title:first-child {
            margin-top: 0;
        }

        .sidebar .nav-link {
            color: var(--text-primary);
            padding: 8px 12px;
            margin: 2px 0;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
        }

        .sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: var(--text-primary);
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .sidebar .nav-link.active i {
            color: white;
        }

        .sidebar .nav-link i {
            width: 18px;
            font-size: 16px;
            color: var(--text-secondary);
            transition: color 0.2s ease;
        }

        .sidebar .nav-link:hover i {
            color: var(--text-primary);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Navbar */
        .navbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 12px 0;
            box-shadow: var(--shadow-sm);
        }

        .navbar .container-fluid {
            padding: 0 32px;
        }

        .navbar-brand {
            font-weight: 600;
            color: var(--text-primary);
        }

        .user-dropdown {
            background: var(--background);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 6px 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-dropdown:hover {
            background: var(--sidebar-hover);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 13px;
        }

        .dropdown-menu {
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-lg);
            border-radius: 12px;
            padding: 8px;
            margin-top: 8px;
        }

        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--sidebar-hover);
        }

        /* Content Container */
        .content-wrapper {
            padding: 32px;
        }

        /* Cards */
        .card {
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            border-radius: 12px;
            margin-bottom: 24px;
            background: var(--card-bg);
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            padding: 20px 24px;
            font-size: 16px;
        }

        .card-body {
            padding: 24px;
        }

        /* Stat Cards */
        .stat-card {
            border-left: 3px solid var(--primary-color);
            padding: 20px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%);
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        /* Buttons */
        .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-secondary {
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .btn-outline-secondary:hover {
            background-color: var(--sidebar-hover);
            border-color: var(--border-color);
            color: var(--text-primary);
        }

        /* Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 12px;
        }

        .badge-status-menunggu {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-status-diperiksa {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-status-selesai,
        .badge-status-lunas {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-status-batal {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge.bg-primary {
            background-color: var(--primary-color) !important;
        }

        /* Alerts */
        .alert {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .alert-success {
            background-color: #ecfdf5;
            border-color: #a7f3d0;
            color: #065f46;
        }

        .alert-danger {
            background-color: #fef2f2;
            border-color: #fecaca;
            color: #991b1b;
        }

        /* Tables */
        .table {
            font-size: 14px;
        }

        .table thead th {
            background-color: var(--background);
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
        }

        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:hover {
            background-color: var(--background);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -260px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .content-wrapper {
                padding: 20px 16px;
            }

            .navbar .container-fluid {
                padding: 0 16px;
            }
        }

        /* Sidebar Toggle Button */
        #sidebarToggle {
            background: var(--background);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        #sidebarToggle:hover {
            background: var(--sidebar-hover);
        }

        /* Page Title */
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }
    </style>
    @yield('styles')
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <a href="#" class="sidebar-logo">
                    <div class="sidebar-logo-icon">
                        <i class="fas fa-clinic-medical"></i>
                    </div>
                    <div class="sidebar-logo-text">
                        <h4>Prima Medika</h4>
                        <small>Sistem Klinik</small>
                    </div>
                </a>
            </div>

            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    @if(auth()->user()->role == 'admin')
                        <li class="nav-section-title">Admin</li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin*') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" 
                                href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i> Pengguna
                            </a>
                        </li>
                    @endif

                    @if(in_array(auth()->user()->role, ['admin', 'petugas']))
                        <li class="nav-section-title">Manajemen</li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('patients*') ? 'active' : '' }}"
                                href="{{ route('patients.index') }}">
                                <i class="fas fa-user-injured"></i> Data Pasien
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('visits*') ? 'active' : '' }}" 
                                href="{{ route('visits.index') }}">
                                <i class="fas fa-calendar-check"></i> Kunjungan
                            </a>
                        </li>
                         <li class="nav-item">
        <a class="nav-link {{ Request::is('services*') ? 'active' : '' }}"
            href="{{ route('services.index') }}">
            <i class="fas fa-hand-holding-medical"></i> Layanan Klinik
        </a>
    </li>
                    @endif

                    @if(auth()->user()->role == 'dokter')
                        <li class="nav-section-title">Dokter</li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('visits*') ? 'active' : '' }}" 
                                href="{{ route('visits.index') }}">
                                <i class="fas fa-list-ul"></i> Antrian Pasien
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('dashboard/dokter') ? 'active' : '' }}"
                                href="{{ route('dokter.dashboard') }}">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role == 'kasir')
                        <li class="nav-section-title">Kasir</li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('transactions*') ? 'active' : '' }}"
                                href="{{ route('transactions.index') }}">
                                <i class="fas fa-cash-register"></i> Transaksi
                            </a>
                        </li>
                    @endif

                    @if(in_array(auth()->user()->role, ['admin', 'dokter']))
                        <li class="nav-section-title">Inventory</li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('medicines*') ? 'active' : '' }}"
                                href="{{ route('medicines.index') }}">
                                <i class="fas fa-pills"></i> Data Obat
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role == 'admin')
                        <li class="nav-section-title">Reporting</li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('reports*') ? 'active' : '' }}"
                                href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar"></i> Laporan
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content w-100">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary d-md-none" type="button" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="navbar-nav ms-auto">
                        <div class="nav-item dropdown">
                            <a class="nav-link user-dropdown d-flex align-items-center gap-2" 
                               href="#" role="button" data-bs-toggle="dropdown">
                                <div class="user-avatar">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="d-none d-md-block">
                                    <div style="font-weight: 600; font-size: 14px; line-height: 1.2;">
                                        {{ auth()->user()->name }}
                                    </div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </div>
                                </div>
                                <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--text-secondary);"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="content-wrapper">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    @yield('scripts')
</body>

</html>