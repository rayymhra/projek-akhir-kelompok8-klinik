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
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #1a2530 100%);
            color: white;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 2px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        
        .card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,.05);
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 2px solid #f1f1f1;
            font-weight: 600;
        }
        
        .stat-card {
            border-left: 4px solid var(--secondary-color);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
        }
        
        .badge-status-menunggu { background-color: #f39c12; }
        .badge-status-diperiksa { background-color: #3498db; }
        .badge-status-selesai { background-color: #27ae60; }
        .badge-status-lunas { background-color: #27ae60; }
        .badge-status-batal { background-color: #e74c3c; }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar.active {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header p-3">
                <h4 class="text-center mb-0">
                    <i class="fas fa-clinic-medical"></i>
                    <br>
                    <small>Klinik Prima Medika</small>
                </h4>
            </div>
            
            <ul class="nav flex-column">
                @if(auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('admin*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> Manajemen Pengguna
                    </a>
                </li>
                @endif
                
                @if(in_array(auth()->user()->role, ['admin', 'petugas']))
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('patients*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                        <i class="fas fa-user-injured"></i> Data Pasien
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('visits*') ? 'active' : '' }}" href="{{ route('visits.index') }}">
                        <i class="fas fa-calendar-check"></i> Kunjungan
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->role == 'dokter')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('dokter*') ? 'active' : '' }}" href="{{ route('dokter.antrian') }}">
                        <i class="fas fa-list-ol"></i> Antrian Pasien
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->role == 'kasir')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('transactions*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                        <i class="fas fa-cash-register"></i> Transaksi
                    </a>
                </li>
                @endif
                
                @if(in_array(auth()->user()->role, ['admin', 'dokter']))
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('medicines*') ? 'active' : '' }}" href="{{ route('medicines.index') }}">
                        <i class="fas fa-pills"></i> Data Obat
                    </a>
                </li>
                @endif
                
                @if(auth()->user()->role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('reports*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-bar"></i> Laporan
                    </a>
                </li>
                @endif
                
                <li class="nav-item mt-auto">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
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
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-2"></i>
                                {{ auth()->user()->name }}
                                <span class="badge bg-primary ms-2">{{ ucfirst(auth()->user()->role) }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
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
            <div class="container-fluid">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        $('#sidebarToggle').click(function() {
            $('.sidebar').toggleClass('active');
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    </script>
    
    @yield('scripts')
</body>
</html>