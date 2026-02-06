@extends('layouts.app')

@section('title', 'Laporan Sistem')

@section('styles')
<style>
    /* Report Type Selector */
    .report-type-selector {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 2rem;
    }

    .report-type-btn {
        flex: 1;
        min-width: 140px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 16px 20px;
        background: var(--card-bg);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-align: center;
        text-decoration: none;
        position: relative;
        overflow: hidden;
    }

    .report-type-btn:hover {
        transform: translateY(-2px);
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
    }

    .report-type-btn.active {
        border-color: var(--primary-color);
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
    }

    .report-type-btn.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
    }

    .report-type-btn i {
        font-size: 1.5rem;
        margin-bottom: 8px;
        color: var(--text-secondary);
        display: block;
    }

    .report-type-btn.active i {
        color: var(--primary-color);
    }

    .report-type-btn .btn-label {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    /* Filter Card */
    .filter-card {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 2rem;
    }

    .filter-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .filter-header i {
        color: var(--primary-color);
        font-size: 1.2rem;
    }

    .filter-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Stats Cards */
    .report-stat-card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: var(--card-bg);
        height: 100%;
    }

    .report-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .report-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .report-stat-card.primary::before { background: linear-gradient(135deg, var(--primary-color) 0%, #4f46e5 100%); }
    .report-stat-card.warning::before { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .report-stat-card.info::before { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .report-stat-card.success::before { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .report-stat-card.danger::before { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

    .report-stat-card .card-body {
        padding: 24px;
        position: relative;
        z-index: 1;
    }

    .stat-icon-container {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        font-size: 1.5rem;
    }

    .report-stat-card.primary .stat-icon-container { background: rgba(99, 102, 241, 0.1); }
    .report-stat-card.warning .stat-icon-container { background: rgba(245, 158, 11, 0.1); }
    .report-stat-card.info .stat-icon-container { background: rgba(14, 165, 233, 0.1); }
    .report-stat-card.success .stat-icon-container { background: rgba(16, 185, 129, 0.1); }
    .report-stat-card.danger .stat-icon-container { background: rgba(239, 68, 68, 0.1); }

    .report-stat-card.primary .stat-icon-container i { color: var(--primary-color); }
    .report-stat-card.warning .stat-icon-container i { color: #f59e0b; }
    .report-stat-card.info .stat-icon-container i { color: #0ea5e9; }
    .report-stat-card.success .stat-icon-container i { color: #10b981; }
    .report-stat-card.danger .stat-icon-container i { color: #ef4444; }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 8px 0;
        color: var(--text-primary);
        line-height: 1;
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 500;
        margin: 0;
    }

    /* Chart Containers */
    .chart-card {
        border: 1px solid var(--border-color);
        border-radius: 16px;
        background: var(--card-bg);
        height: 100%;
        transition: all 0.3s ease;
    }

    .chart-card:hover {
        box-shadow: var(--shadow-md);
    }

    .chart-card .card-header {
        background: transparent;
        border-bottom: 1px solid var(--border-color);
        padding: 20px 24px;
    }

    .chart-card .card-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chart-card .card-body {
        padding: 24px;
    }

    /* Table Container */
    .table-container {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
    }

    .table-container .table-header {
        background: #f8fafc;
        padding: 20px 24px;
        border-bottom: 1px solid var(--border-color);
    }

    .table-container .table-header h6 {
        margin: 0;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-container .table-responsive {
        margin: 0;
    }

    /* Medicine specific styles */
    .medicine-status-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .medicine-status-low {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }
    
    .medicine-status-out {
        background-color: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    .medicine-status-good {
        background-color: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    
    .medicine-expiry-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 500;
    }
    
    .expired {
        background-color: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    .expiring-soon {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    /* Income List */
    .income-list {
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-color);
    }

    .income-list .list-group-item {
        border: none;
        border-bottom: 1px solid var(--border-color);
        padding: 16px 20px;
        transition: background-color 0.2s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .income-list .list-group-item:last-child {
        border-bottom: none;
    }

    .income-list .list-group-item:hover {
        background-color: #f8fafc;
    }

    .income-month {
        font-weight: 500;
        color: var(--text-primary);
    }

    .income-amount {
        font-weight: 600;
        font-family: 'Inter', monospace;
        color: #10b981;
    }

    /* Export Button */
    .export-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }

    /* Date Input Group */
    .date-input-group {
        position: relative;
    }

    .date-input-group i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        pointer-events: none;
    }

    .date-input-group input {
        padding-left: 40px !important;
    }

    /* Empty State */
    .report-empty-state {
        padding: 60px 20px;
        text-align: center;
        color: var(--text-secondary);
    }

    .report-empty-state i {
        font-size: 3rem;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    .report-empty-state h5 {
        margin: 1rem 0 0.5rem;
        color: var(--text-secondary);
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .report-type-btn {
            min-width: calc(50% - 4px);
            padding: 12px 16px;
        }

        .report-type-btn i {
            font-size: 1.25rem;
        }

        .filter-card {
            padding: 20px;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .stat-icon-container {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .report-type-btn {
            min-width: 100%;
        }

        .stat-value {
            font-size: 1.25rem;
        }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-1">Laporan Sistem</h1>
                <p class="page-subtitle">Analisis dan statistik lengkap sistem klinik</p>
            </div>
        </div>

        <!-- Report Type Selector -->
        <div class="report-type-selector mb-4">
            @php
                $currentType = $filter['type'] ?? 'visits';
            @endphp
            
            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'visits'])) }}" 
               class="report-type-btn {{ $currentType == 'visits' ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i>
                <span class="btn-label">Kunjungan</span>
            </a>
            
            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'transactions'])) }}" 
               class="report-type-btn {{ $currentType == 'transactions' ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i>
                <span class="btn-label">Transaksi</span>
            </a>
            
            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'patients'])) }}" 
               class="report-type-btn {{ $currentType == 'patients' ? 'active' : '' }}">
                <i class="fas fa-user-injured"></i>
                <span class="btn-label">Pasien</span>
            </a>
            
            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'medicines'])) }}" 
               class="report-type-btn {{ $currentType == 'medicines' ? 'active' : '' }}">
                <i class="fas fa-pills"></i>
                <span class="btn-label">Obat</span>
            </a>
            
            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'income'])) }}" 
               class="report-type-btn {{ $currentType == 'income' ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span class="btn-label">Pendapatan</span>
            </a>
        </div>

        <!-- Filter Card -->
        <div class="filter-card mb-4">
            <div class="filter-header">
                <i class="fas fa-sliders-h"></i>
                <h6>Filter Laporan</h6>
            </div>
            
            <form method="GET" class="row g-3">
                <input type="hidden" name="type" value="{{ $currentType }}">
                
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Tanggal Mulai</label>
                    <div class="date-input-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" class="form-control form-control-sm" 
                               name="start_date" value="{{ $filter['start_date'] ?? '' }}" required>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Tanggal Akhir</label>
                    <div class="date-input-group">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" class="form-control form-control-sm" 
                               name="end_date" value="{{ $filter['end_date'] ?? '' }}" required>
                    </div>
                </div>
                
                @if($currentType == 'visits')
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Dokter</label>
                    <select name="doctor_id" class="form-select form-select-sm">
                        <option value="">Semua Dokter</option>
                        @foreach(\App\Models\User::where('role', 'dokter')->get() as $doctor)
                        <option value="{{ $doctor->id }}" 
                            {{ ($filter['doctor_id'] ?? '') == $doctor->id ? 'selected' : '' }}>
                            {{ $doctor->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="col-md-3"></div>
                @endif
                
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="fas fa-filter me-2"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('reports.index', ['type' => $currentType]) }}" 
                       class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-redo"></i>
                    </a>
                    @if(!empty($filter['start_date']) && !empty($filter['end_date']))
                    <a href="{{ route('reports.export', [
                        'type' => $filter['type'] ?? 'visits',
                        'start_date' => $filter['start_date'] ?? null,
                        'end_date' => $filter['end_date'] ?? null,
                        'doctor_id' => $filter['doctor_id'] ?? null
                    ]) }}" class="btn btn-success btn-sm export-btn" target="_blank">
                        <i class="fas fa-file-export me-1"></i> Export
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Report Content -->
        @php
        $reportType = $reports['type'] ?? 'visits';
        @endphp
        
        @if($reportType == 'visits')
        <!-- Visit Report -->
        <div class="row mb-4">
            @foreach([
                ['value' => $reports['stats']['total'] ?? 0, 'label' => 'Total Kunjungan', 'icon' => 'calendar-check', 'class' => 'primary'],
                ['value' => $reports['stats']['menunggu'] ?? 0, 'label' => 'Menunggu', 'icon' => 'clock', 'class' => 'warning'],
                ['value' => $reports['stats']['diperiksa'] ?? 0, 'label' => 'Diperiksa', 'icon' => 'user-md', 'class' => 'info'],
                ['value' => $reports['stats']['selesai'] ?? 0, 'label' => 'Selesai', 'icon' => 'check-circle', 'class' => 'success']
            ] as $stat)
            <div class="col-md-3 mb-3">
                <div class="report-stat-card {{ $stat['class'] }}">
                    <div class="card-body">
                        <div class="stat-icon-container">
                            <i class="fas fa-{{ $stat['icon'] }}"></i>
                        </div>
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <p class="stat-label">{{ $stat['label'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="table-container">
                    <div class="table-header">
                        <h6><i class="fas fa-list-ul"></i> Detail Kunjungan</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th width="100">Tanggal</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th width="100">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports['data'] as $visit)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration }}</td>
                                    <td>{{ $visit->tanggal_kunjungan->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $visit->patient->nama }}</strong>
                                            <small class="text-muted">{{ $visit->patient->no_rekam_medis }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $visit->doctor->name }}</td>
                                    <td>
                                        <span class="badge badge-status-{{ $visit->status }}">
                                            {{ ucfirst($visit->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="report-empty-state">
                                            <i class="fas fa-calendar-times"></i>
                                            <h5>Tidak ada data kunjungan</h5>
                                            <p class="mb-0">Tidak ditemukan kunjungan dalam periode yang dipilih.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="chart-card">
                    <div class="card-header">
                        <h6><i class="fas fa-chart-bar"></i> Distribusi Harian</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="dailyVisitChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($reportType == 'transactions')
        <!-- Transaction Report -->
        <div class="row mb-4">
            @foreach([
                ['value' => $reports['stats']['total'] ?? 0, 'label' => 'Total Transaksi', 'icon' => 'receipt', 'class' => 'primary'],
                ['value' => 'Rp ' . number_format($reports['stats']['total_income'] ?? 0, 0, ',', '.'), 'label' => 'Total Pendapatan', 'icon' => 'wallet', 'class' => 'success'],
                ['value' => $reports['stats']['lunas'] ?? 0, 'label' => 'Lunas', 'icon' => 'check-circle', 'class' => 'info'],
                ['value' => $reports['stats']['menunggu'] ?? 0, 'label' => 'Menunggu', 'icon' => 'clock', 'class' => 'warning']
            ] as $stat)
            <div class="col-md-3 mb-3">
                <div class="report-stat-card {{ $stat['class'] }}">
                    <div class="card-body">
                        <div class="stat-icon-container">
                            <i class="fas fa-{{ $stat['icon'] }}"></i>
                        </div>
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <p class="stat-label">{{ $stat['label'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="row">
            <div class="col-md-7 mb-4">
                <div class="table-container">
                    <div class="table-header">
                        <h6><i class="fas fa-money-bill-wave"></i> Detail Transaksi</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th width="120">Tanggal</th>
                                    <th>Pasien</th>
                                    <th width="120">Total</th>
                                    <th width="100">Metode</th>
                                    <th width="100">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports['data'] as $transaction)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong>{{ $transaction->visit->patient->nama }}</strong>
                                            <small class="text-muted">{{ $transaction->visit->patient->no_rekam_medis }}</small>
                                        </div>
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</td>
                                    <td>
                                        @switch($transaction->metode_pembayaran)
                                            @case('tunai')
                                                <span class="badge bg-primary">Tunai</span>
                                                @break
                                            @case('transfer')
                                                <span class="badge bg-success">Transfer</span>
                                                @break
                                            @case('qris')
                                                <span class="badge bg-info">QRIS</span>
                                                @break
                                            @case('e-wallet')
                                                <span class="badge bg-warning">E-Wallet</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <span class="badge badge-status-{{ $transaction->status }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="report-empty-state">
                                            <i class="fas fa-receipt"></i>
                                            <h5>Tidak ada data transaksi</h5>
                                            <p class="mb-0">Tidak ditemukan transaksi dalam periode yang dipilih.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-4">
                <div class="chart-card">
                    <div class="card-header">
                        <h6><i class="fas fa-chart-pie"></i> Metode Pembayaran</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="paymentMethodChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($reportType == 'patients')
        <!-- Patient Report -->
        <div class="row mb-4">
            @foreach([
                ['value' => $reports['stats']['total'] ?? 0, 'label' => 'Total Pasien', 'icon' => 'users', 'class' => 'primary'],
                ['value' => $reports['stats']['male'] ?? 0, 'label' => 'Laki-laki', 'icon' => 'male', 'class' => 'info'],
                ['value' => $reports['stats']['female'] ?? 0, 'label' => 'Perempuan', 'icon' => 'female', 'class' => 'warning'],
                ['value' => $reports['stats']['new_patients'] ?? 0, 'label' => 'Pasien Baru', 'icon' => 'user-plus', 'class' => 'success']
            ] as $stat)
            <div class="col-md-3 mb-3">
                <div class="report-stat-card {{ $stat['class'] }}">
                    <div class="card-body">
                        <div class="stat-icon-container">
                            <i class="fas fa-{{ $stat['icon'] }}"></i>
                        </div>
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <p class="stat-label">{{ $stat['label'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="row">
            <div class="col-md-7 mb-4">
                <div class="table-container">
                    <div class="table-header">
                        <h6><i class="fas fa-list-ul"></i> Daftar Pasien</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th width="120">No. RM</th>
                                    <th>Nama</th>
                                    <th width="120">Jenis Kelamin</th>
                                    <th width="80">Umur</th>
                                    <th width="120">Total Kunjungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports['data'] as $patient)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration }}</td>
                                    <td><strong>{{ $patient->no_rekam_medis }}</strong></td>
                                    <td>{{ $patient->nama }}</td>
                                    <td>
                                        <span class="badge {{ $patient->jenis_kelamin == 'L' ? 'bg-info' : 'bg-warning' }}">
                                            {{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </span>
                                    </td>
                                    <td>{{ $patient->umur }} tahun</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $patient->visits_count }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="report-empty-state">
                                            <i class="fas fa-user-times"></i>
                                            <h5>Tidak ada data pasien</h5>
                                            <p class="mb-0">Tidak ditemukan pasien dalam periode yang dipilih.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-5 mb-4">
                <div class="chart-card">
                    <div class="card-header">
                        <h6><i class="fas fa-chart-pie"></i> Distribusi Usia</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="ageDistributionChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($reportType == 'medicines')
        <!-- Medicine Report -->
        <div class="row mb-4">
            @foreach([
                ['value' => $reports['stats']['total'] ?? 0, 'label' => 'Total Jenis Obat', 'icon' => 'pills', 'class' => 'primary'],
                ['value' => $reports['stats']['total_stock'] ?? 0, 'label' => 'Total Stok', 'icon' => 'boxes', 'class' => 'info'],
                ['value' => 'Rp ' . number_format($reports['stats']['total_value'] ?? 0, 0, ',', '.'), 'label' => 'Nilai Stok', 'icon' => 'money-bill-wave', 'class' => 'success'],
                ['value' => $reports['stats']['low_stock'] ?? 0, 'label' => 'Stok Rendah', 'icon' => 'exclamation-triangle', 'class' => 'warning']
            ] as $stat)
            <div class="col-md-3 mb-3">
                <div class="report-stat-card {{ $stat['class'] }}">
                    <div class="card-body">
                        <div class="stat-icon-container">
                            <i class="fas fa-{{ $stat['icon'] }}"></i>
                        </div>
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <p class="stat-label">{{ $stat['label'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="table-container">
                    <div class="table-header">
                        <h6><i class="fas fa-list-ul"></i> Daftar Obat</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th width="100">Kode</th>
                                    <th>Nama Obat</th>
                                    <th width="100">Jenis</th>
                                    <th width="80">Stok</th>
                                    <th width="120">Harga</th>
                                    <th width="120">Kadaluwarsa</th>
                                    <th width="100">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reports['data'] as $medicine)
                                @php
                                    $expiryStatus = '';
                                    if($medicine->expired_date) {
                                        if($medicine->expired_date->isPast()) {
                                            $expiryStatus = 'expired';
                                        } elseif($medicine->expired_date->diffInDays(now()) <= 30) {
                                            $expiryStatus = 'expiring-soon';
                                        }
                                    }
                                    
                                    $stockStatus = 'medicine-status-good';
                                    if($medicine->stok == 0) {
                                        $stockStatus = 'medicine-status-out';
                                    } elseif($medicine->stok <= 10) {
                                        $stockStatus = 'medicine-status-low';
                                    }
                                @endphp
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration }}</td>
                                    <td><strong>{{ $medicine->kode_obat }}</strong></td>
                                    <td>{{ $medicine->nama_obat }}</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $medicine->jenis_obat }}</span>
                                    </td>
                                    <td>
                                        <span class="medicine-status-badge {{ $stockStatus }}">
                                            {{ $medicine->stok }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">Rp {{ number_format($medicine->harga, 0, ',', '.') }}</td>
                                    <td>
                                        @if($medicine->expired_date)
                                            <div class="d-flex flex-column">
                                                <span>{{ $medicine->expired_date->format('d/m/Y') }}</span>
                                                @if($expiryStatus)
                                                <small class="medicine-expiry-badge {{ $expiryStatus }}">
                                                    @if($expiryStatus == 'expired')
                                                    <i class="fas fa-exclamation-circle me-1"></i>Kedaluwarsa
                                                    @else
                                                    <i class="fas fa-clock me-1"></i>Segera
                                                    @endif
                                                </small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($medicine->stok == 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($medicine->stok <= 10)
                                            <span class="badge bg-warning">Rendah</span>
                                        @else
                                            <span class="badge bg-success">Aman</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="report-empty-state">
                                            <i class="fas fa-pills"></i>
                                            <h5>Tidak ada data obat</h5>
                                            <p class="mb-0">Tidak ditemukan obat dalam periode yang dipilih.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="chart-card">
                    <div class="card-header">
                        <h6><i class="fas fa-chart-pie"></i> Distribusi Jenis</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="medicineTypeChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        @if($reportType == 'income')
        <!-- Income Report -->
        <div class="row mb-4">
            @foreach([
                ['value' => 'Rp ' . number_format($reports['stats']['total_income'] ?? 0, 0, ',', '.'), 'label' => 'Total Pendapatan', 'icon' => 'money-bill-wave', 'class' => 'success'],
                ['value' => 'Rp ' . number_format($reports['stats']['average_daily'] ?? 0, 0, ',', '.'), 'label' => 'Rata-rata Harian', 'icon' => 'chart-line', 'class' => 'info'],
                ['value' => 'Rp ' . number_format($reports['stats']['max_daily'] ?? 0, 0, ',', '.'), 'label' => 'Maksimal Harian', 'icon' => 'chart-bar', 'class' => 'warning'],
                ['value' => $reports['stats']['transaction_count'] ?? 0, 'label' => 'Jumlah Transaksi', 'icon' => 'receipt', 'class' => 'primary']
            ] as $stat)
            <div class="col-md-3 mb-3">
                <div class="report-stat-card {{ $stat['class'] }}">
                    <div class="card-body">
                        <div class="stat-icon-container">
                            <i class="fas fa-{{ $stat['icon'] }}"></i>
                        </div>
                        <div class="stat-value">{{ $stat['value'] }}</div>
                        <p class="stat-label">{{ $stat['label'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="chart-card">
                    <div class="card-header">
                        <h6><i class="fas fa-chart-line"></i> Tren Pendapatan Harian</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="incomeTrendChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="chart-card">
                    <div class="card-header">
                        <h6><i class="fas fa-calendar-alt"></i> Pendapatan Bulanan</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="income-list">
                            @forelse($reports['monthly_income'] ?? [] as $month => $income)
                            <div class="list-group-item">
                                <span class="income-month">{{ date('F Y', strtotime($month . '-01')) }}</span>
                                <span class="income-amount">Rp {{ number_format($income, 0, ',', '.') }}</span>
                            </div>
                            @empty
                            <div class="list-group-item text-center py-4">
                                <i class="fas fa-chart-line text-muted mb-2"></i>
                                <p class="text-muted mb-0">Tidak ada data pendapatan</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportType = '{{ $reports["type"] ?? "visits" }}';
        
        if (reportType === 'visits') {
            renderVisitChart();
        } else if (reportType === 'transactions') {
            renderPaymentMethodChart();
        } else if (reportType === 'patients') {
            renderAgeDistributionChart();
        } else if (reportType === 'medicines') {
            renderMedicineTypeChart();
        } else if (reportType === 'income') {
            renderIncomeTrendChart();
        }
        
        function renderVisitChart() {
            const ctx = document.getElementById('dailyVisitChart').getContext('2d');
            const data = @json($reports['grouped'] ?? []);
            
            const labels = Object.keys(data).map(date => {
                return new Date(date).toLocaleDateString('id-ID', { 
                    day: 'numeric',
                    month: 'short'
                });
            });
            
            const datasets = [{
                label: 'Total Kunjungan',
                data: Object.values(data).map(d => d.total),
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 1,
                borderRadius: 6
            }];
            
            new Chart(ctx, {
                type: 'bar',
                data: { labels, datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
        
        function renderPaymentMethodChart() {
            const ctx = document.getElementById('paymentMethodChart').getContext('2d');
            const data = @json($reports['payment_methods'] ?? []);
            
            const colors = {
                'tunai': 'rgba(99, 102, 241, 0.8)',
                'transfer': 'rgba(16, 185, 129, 0.8)',
                'qris': 'rgba(14, 165, 233, 0.8)',
                'e-wallet': 'rgba(245, 158, 11, 0.8)'
            };
            
            const labels = Object.keys(data).map(method => {
                const methodNames = {
                    'tunai': 'Tunai',
                    'transfer': 'Transfer',
                    'qris': 'QRIS',
                    'e-wallet': 'E-Wallet'
                };
                return methodNames[method] || method;
            });
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: Object.keys(data).map(method => colors[method] || '#ccc'),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20
                            }
                        }
                    }
                }
            });
        }
        
        function renderAgeDistributionChart() {
            const ctx = document.getElementById('ageDistributionChart').getContext('2d');
            const data = @json($reports['age_groups'] ?? []);
            
            const ageColors = [
                'rgba(239, 68, 68, 0.8)',
                'rgba(99, 102, 241, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(139, 92, 246, 0.8)'
            ];
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: ageColors.slice(0, Object.keys(data).length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20
                            }
                        }
                    }
                }
            });
        }
        
        function renderMedicineTypeChart() {
            const ctx = document.getElementById('medicineTypeChart').getContext('2d');
            const data = @json($reports['medicine_types'] ?? []);
            
            const colors = [
                'rgba(99, 102, 241, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(14, 165, 233, 0.8)'
            ];
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: colors.slice(0, Object.keys(data).length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20
                            }
                        }
                    }
                }
            });
        }
        
        function renderIncomeTrendChart() {
            const ctx = document.getElementById('incomeTrendChart').getContext('2d');
            const data = @json($reports['daily_income'] ?? []);
            
            const labels = Object.keys(data).map(date => {
                return new Date(date).toLocaleDateString('id-ID', { 
                    day: 'numeric',
                    month: 'short'
                });
            });
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: Object.values(data),
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection