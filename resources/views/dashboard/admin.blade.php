@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="dashboard-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard Admin</h1>
            <p class="page-subtitle">Selamat datang kembali, {{ auth()->user()->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" onclick="window.location.reload()">
                <i class="fas fa-sync-alt me-2"></i>Refresh
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-primary">
                <i class="fas fa-chart-line me-2"></i>Lihat Laporan
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-user-injured"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Pasien</div>
                <div class="stat-value">{{ number_format($totalPatients) }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-primary">
                        <i class="fas fa-users me-1"></i>Terdaftar
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Kunjungan Hari Ini</div>
                <div class="stat-value">{{ number_format($todayVisits) }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-success">
                        <i class="fas fa-arrow-up me-1"></i>Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Pendapatan Bulan Ini</div>
                <div class="stat-value" style="font-size: 20px;">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-info">
                        <i class="fas fa-calendar-alt me-1"></i>{{ now()->format('F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card {{ $lowStockMedicines > 0 ? 'stat-card-warning' : '' }}">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <i class="fas fa-pills"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Stok Obat Rendah</div>
                <div class="stat-value">{{ number_format($lowStockMedicines) }}</div>
                <div class="stat-meta">
                    @if($lowStockMedicines > 0)
                        <span class="badge badge-soft-warning">
                            <i class="fas fa-exclamation-triangle me-1"></i>Perlu Restock
                        </span>
                    @else
                        <span class="badge badge-soft-success">
                            <i class="fas fa-check-circle me-1"></i>Stok Aman
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Recent Visits -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Kunjungan Terbaru</h5>
                    <small class="text-muted">Aktivitas pasien terkini</small>
                </div>
                <a href="{{ route('visits.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Tanggal</th>
                                <th style="width: 120px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentVisits as $visit)
                            <tr>
                                <td>
                                    <div class="avatar-sm">
                                        {{ substr($visit->patient->nama, 0, 1) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $visit->patient->nama }}</div>
                                    <small class="text-muted">{{ $visit->patient->no_rekam_medis }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-user-md text-muted"></i>
                                        <span>{{ $visit->doctor->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $visit->tanggal_kunjungan->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $visit->tanggal_kunjungan->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-status-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada kunjungan hari ini</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- User Statistics -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Statistik Pengguna</h5>
                <small class="text-muted">Distribusi role sistem</small>
            </div>
            <div class="card-body">
                <div class="chart-container mb-4">
                    <canvas id="userStatsChart" height="200"></canvas>
                </div>
                
                <div class="user-stats-list">
                    <div class="user-stat-item">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-dot" style="background: #6366f1;"></div>
                            <span class="stat-name">Admin</span>
                        </div>
                        <span class="stat-count">{{ $userStats['admin'] }}</span>
                    </div>
                    <div class="user-stat-item">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-dot" style="background: #8b5cf6;"></div>
                            <span class="stat-name">Petugas</span>
                        </div>
                        <span class="stat-count">{{ $userStats['petugas'] }}</span>
                    </div>
                    <div class="user-stat-item">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-dot" style="background: #ec4899;"></div>
                            <span class="stat-name">Dokter</span>
                        </div>
                        <span class="stat-count">{{ $userStats['dokter'] }}</span>
                    </div>
                    <div class="user-stat-item">
                        <div class="d-flex align-items-center gap-2">
                            <div class="stat-dot" style="background: #f59e0b;"></div>
                            <span class="stat-name">Kasir</span>
                        </div>
                        <span class="stat-count">{{ $userStats['kasir'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('styles')
<style>
    /* Stats Cards */
    .stat-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        transition: all 0.2s ease;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-card-warning {
        background: linear-gradient(135deg, rgba(251, 191, 36, 0.05) 0%, rgba(239, 68, 68, 0.05) 100%);
        border-color: rgba(251, 191, 36, 0.2);
    }
    
    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        flex-shrink: 0;
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 6px;
        font-weight: 500;
    }
    
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
        margin-bottom: 8px;
    }
    
    .stat-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    /* Badge Soft Variants */
    .badge-soft-primary {
        background-color: rgba(99, 102, 241, 0.1);
        color: #4f46e5;
    }
    
    .badge-soft-success {
        background-color: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    
    .badge-soft-info {
        background-color: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }
    
    .badge-soft-warning {
        background-color: rgba(251, 191, 36, 0.1);
        color: #d97706;
    }
    
    /* Avatar */
    .avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
    
    /* User Stats */
    .chart-container {
        position: relative;
        height: 200px;
    }
    
    .user-stats-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .user-stat-item {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 12px;
        background: var(--background);
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .user-stat-item:hover {
        background: var(--sidebar-hover);
    }
    
    .stat-dot {
        width: 12px;
        height: 12px;
        border-radius: 3px;
    }
    
    .stat-name {
        font-size: 14px;
        color: var(--text-primary);
        font-weight: 500;
        flex: 1;
    }
    
    .stat-count {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    /* Quick Action Cards */
    .quick-action-card {
        display: flex;
        gap: 16px;
        padding: 20px;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        text-decoration: none;
        color: var(--text-primary);
        transition: all 0.2s ease;
        height: 100%;
    }
    
    .quick-action-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }
    
    .quick-action-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        flex-shrink: 0;
    }
    
    .quick-action-content h6 {
        font-size: 15px;
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--text-primary);
    }
    
    .quick-action-content small {
        font-size: 12px;
        color: var(--text-secondary);
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('userStatsChart').getContext('2d');
    const userStatsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Petugas', 'Dokter', 'Kasir'],
            datasets: [{
                data: [
                    {{ $userStats['admin'] }},
                    {{ $userStats['petugas'] }},
                    {{ $userStats['dokter'] }},
                    {{ $userStats['kasir'] }}
                ],
                backgroundColor: [
                    '#6366f1',
                    '#8b5cf6',
                    '#ec4899',
                    '#f59e0b'
                ],
                borderWidth: 0,
                borderRadius: 4,
                spacing: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderRadius: 8,
                    titleFont: {
                        size: 13,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 12
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection