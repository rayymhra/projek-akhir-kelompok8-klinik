@extends('layouts.app')

@section('title', 'Manajemen Kunjungan')

@section('styles')
<style>
    /* Enhanced status badges */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    /* Card hover effects */
    .stat-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.3) 100%);
    }
    
    /* Current patient highlight */
    .current-patient-card {
        background: linear-gradient(135deg, #e6f3ff 0%, #f0f9ff 100%);
        border: 2px solid #3b82f6;
        border-radius: 16px;
        animation: pulse-glow 3s infinite;
    }
    
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.2); }
        50% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    }
    
    /* Queue number styling */
    .queue-number {
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 1.2rem;
        padding: 8px 16px;
        border-radius: 10px;
        display: inline-block;
    }
    
    .priority-queue {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .priority-queue::after {
        content: '★';
        position: absolute;
        top: 2px;
        right: 6px;
        font-size: 0.7rem;
    }
    
    /* Action buttons styling */
    .action-btn-group {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .action-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
    }
    
    /* Table improvements */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.05);
    }
    
    /* Filter section styling */
    .filter-card {
        background: var(--background);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        .stat-card .fa-2x {
            font-size: 1.5rem;
        }
        
        .action-btn-group {
            justify-content: center;
        }
        
        .filter-card .row {
            gap: 12px;
        }
    }
    
    /* Empty state styling */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: var(--text-secondary);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 16px;
        opacity: 0.3;
    }
    
    /* Live clock animation */
    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    #liveClock {
        animation: blink 2s infinite;
    }
    
    /* Doctor queue specific */
    .doctor-queue-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        color: white;
        border-radius: 12px 12px 0 0;
    }
    
    /* Quick action buttons */
    .quick-status-btn {
        min-width: 80px;
        border-radius: 8px;
        font-size: 12px;
        padding: 6px 12px;
    }
</style>
@endsection

@section('content')
@if(auth()->user()->role == 'dokter')
<!-- =========================================== -->
<!-- DOCTOR'S QUEUE VIEW (ANTRIAN KONSULTASI) -->
<!-- =========================================== -->
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">
            <!-- Header with Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card stat-card border-start border-warning border-4">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-warning mb-1 fw-semibold">MENUNGGU</h6>
                                    <h2 class="mb-0">{{ $visits->where('status', 'menunggu')->count() }}</h2>
                                    <small class="text-muted">Pasien</small>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card border-start border-info border-4">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-info mb-1 fw-semibold">DIPERIKSA</h6>
                                    <h2 class="mb-0">{{ $visits->where('status', 'diperiksa')->count() }}</h2>
                                    <small class="text-muted">Pasien</small>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-user-md fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card border-start border-success border-4">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-success mb-1 fw-semibold">SELESAI</h6>
                                    <h2 class="mb-0">{{ $visits->where('status', 'selesai')->count() }}</h2>
                                    <small class="text-muted">Pasien</small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Patient Card -->
            @php
                $currentPatient = $visits->where('status', 'diperiksa')->first();
            @endphp
            @if($currentPatient)
            <div class="card current-patient-card mb-4 border-0">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-user-md fa-2x text-primary"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center mb-2">
                                <h4 class="mb-0 me-3">{{ $currentPatient->patient->nama }}</h4>
                                <span class="queue-number bg-primary text-white">
                                    {{ $currentPatient->nomor_antrian_full ?? 'A-' . str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            <div class="row g-3">
                                <div class="col-auto">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-id-card me-1"></i> RM: {{ $currentPatient->patient->no_rekam_medis }}
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-birthday-cake me-1"></i> {{ $currentPatient->patient->umur }} tahun
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-hospital me-1"></i> {{ $currentPatient->poli ?? 'Umum' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('medical-records.create', $currentPatient) }}" 
                               class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-stethoscope me-2"></i> Input Pemeriksaan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Queue Table -->
            <div class="card">
                <div class="card-header doctor-queue-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="fas fa-user-md me-2"></i> Antrian Konsultasi
                            </h5>
                            <small class="opacity-75">Dokter: {{ auth()->user()->name }}</small>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-white text-dark px-3 py-2">
                                <i class="fas fa-clock me-1"></i>
                                <span id="liveClock">00:00:00</span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($visits->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="100">Antrian</th>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th width="140" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visits as $visit)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="queue-number {{ ($visit->prioritas ?? 'normal') == 'prioritas' ? 'priority-queue' : 'bg-primary' }} mb-1">
                                                {{ $visit->nomor_antrian_full ?? 'A-' . str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                                            </span>
                                            @if(($visit->prioritas ?? 'normal') == 'prioritas')
                                            <small class="text-danger fw-semibold">
                                                <i class="fas fa-exclamation-circle me-1"></i>Prioritas
                                            </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <strong class="mb-1">{{ $visit->patient->nama }}</strong>
                                            <div class="d-flex gap-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-id-card me-1"></i>{{ $visit->patient->no_rekam_medis }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="fas fa-birthday-cake me-1"></i>{{ $visit->patient->umur }} thn
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $visit->poli ?? 'Umum' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $visit->created_at->format('H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="status-badge badge-status-{{ $visit->status }}">
                                            <i class="fas fa-circle fa-xs"></i>
                                            {{ ucfirst($visit->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-btn-group justify-content-center">
                                            <a href="{{ route('patients.show', $visit->patient) }}" 
                                               class="action-btn btn btn-sm btn-light" 
                                               data-bs-toggle="tooltip" 
                                               title="Detail Pasien">
                                                <i class="fas fa-user text-info"></i>
                                            </a>
                                            
                                            @if($visit->status == 'menunggu')
                                            <form action="{{ route('visits.updateStatus', $visit) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="diperiksa">
                                                <button type="submit" 
                                                        class="action-btn btn btn-sm btn-primary"
                                                        data-bs-toggle="tooltip" 
                                                        title="Mulai Pemeriksaan">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($visit->status == 'diperiksa')
                                            <form action="{{ route('visits.updateStatus', $visit) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="selesai">
                                                <button type="submit" 
                                                        class="action-btn btn btn-sm btn-success"
                                                        data-bs-toggle="tooltip" 
                                                        title="Selesaikan">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <i class="fas fa-users-slash"></i>
                        <h5 class="mt-3 mb-2">Tidak ada pasien dalam antrian</h5>
                        <p class="text-muted">Tidak ada pasien yang terdaftar untuk konsultasi hari ini.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@else
<!-- =========================================== -->
<!-- ADMIN/STAFF VIEW (MANAJEMEN KUNJUNGAN) -->
<!-- =========================================== -->
<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-1">Manajemen Kunjungan</h1>
                <p class="page-subtitle">Kelola semua kunjungan pasien di klinik</p>
            </div>
            <a href="{{ route('visits.create') }}" class="btn btn-primary px-4">
                <i class="fas fa-plus-circle me-2"></i>Kunjungan Baru
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card border-start border-warning border-4">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-warning mb-1 fw-semibold">MENUNGGU</h6>
                                <h2 class="mb-0">{{ $visits->where('status', 'menunggu')->count() }}</h2>
                                <small class="text-muted">Pasien</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-start border-info border-4">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-info mb-1 fw-semibold">DIPERIKSA</h6>
                                <h2 class="mb-0">{{ $visits->where('status', 'diperiksa')->count() }}</h2>
                                <small class="text-muted">Pasien</small>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-user-md fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-start border-success border-4">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-success mb-1 fw-semibold">SELESAI</h6>
                                <h2 class="mb-0">{{ $visits->where('status', 'selesai')->count() }}</h2>
                                <small class="text-muted">Pasien</small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card border-start border-primary border-4">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-primary mb-1 fw-semibold">TOTAL</h6>
                                <h2 class="mb-0">{{ $visits->count() }}</h2>
                                <small class="text-muted">Kunjungan</small>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card filter-card mb-4">
            <div class="card-body p-3">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diperiksa" {{ request('status') == 'diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Tanggal</label>
                        <input type="date" class="form-control form-control-sm" name="tanggal" 
                               value="{{ request('tanggal') }}" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Dokter</label>
                        <select name="doctor_id" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Dokter</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Cari Pasien</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Nama atau no. RM..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request()->anyFilled(['status', 'tanggal', 'doctor_id', 'search']))
                            <a href="{{ route('visits.index') }}" class="btn btn-outline-danger" type="button">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="fas fa-list-ul me-2"></i> Daftar Kunjungan
                </h5>
            </div>
            <div class="card-body p-0">
                @if($visits->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th width="100">Antrian</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Poli</th>
                                <th width="100">Tanggal</th>
                                <th width="100">Status</th>
                                <th width="180" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visits as $visit)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        <span class="queue-number {{ ($visit->prioritas ?? 'normal') == 'prioritas' ? 'priority-queue' : 'bg-primary' }} mb-1">
                                            {{ $visit->nomor_antrian_full ?? 'A-' . str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                        @if(($visit->prioritas ?? 'normal') == 'prioritas')
                                        <small class="text-danger fw-semibold">
                                            <i class="fas fa-exclamation-circle me-1"></i>Prioritas
                                        </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="mb-1">{{ $visit->patient->nama }}</strong>
                                        <small class="text-muted">
                                            <i class="fas fa-id-card me-1"></i>{{ $visit->patient->no_rekam_medis }}
                                        </small>
                                    </div>
                                </td>
                                <td>{{ $visit->doctor->name }}</td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $visit->poli ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="text-muted">
                                    {{ $visit->tanggal_kunjungan->format('d/m/Y') }}
                                </td>
                                <td>
                                    <span class="status-badge badge-status-{{ $visit->status }}">
                                        <i class="fas fa-circle fa-xs"></i>
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-btn-group justify-content-center">
                                        <!-- Quick Status Buttons -->
                                        @if($visit->status != 'menunggu')
                                        <form action="{{ route('visits.updateStatus', $visit) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="menunggu">
                                            <button type="submit" class="btn btn-sm btn-warning quick-status-btn"
                                                    data-bs-toggle="tooltip" title="Set ke Menunggu">
                                                <i class="fas fa-clock"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if($visit->status != 'diperiksa')
                                        <form action="{{ route('visits.updateStatus', $visit) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="diperiksa">
                                            <button type="submit" class="btn btn-sm btn-info quick-status-btn"
                                                    data-bs-toggle="tooltip" title="Set ke Diperiksa">
                                                <i class="fas fa-user-md"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if($visit->status != 'selesai')
                                        <form action="{{ route('visits.updateStatus', $visit) }}" 
                                              method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit" class="btn btn-sm btn-success quick-status-btn"
                                                    data-bs-toggle="tooltip" title="Set ke Selesai">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <!-- Dropdown Menu -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('patients.show', $visit->patient) }}">
                                                        <i class="fas fa-user text-info me-2"></i>Detail Pasien
                                                    </a>
                                                </li>
                                                @if($visit->status == 'selesai' && !$visit->transaction)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('transactions.create', ['visit' => $visit->id]) }}">
                                                        <i class="fas fa-cash-register text-secondary me-2"></i>Buat Transaksi
                                                    </a>
                                                </li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <h6 class="dropdown-header">Ubah Status</h6>
                                                </li>
                                                @if($visit->status != 'menunggu')
                                                <li>
                                                    <form action="{{ route('visits.updateStatus', $visit) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="menunggu">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-clock text-warning me-2"></i>Menunggu
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($visit->status != 'diperiksa')
                                                <li>
                                                    <form action="{{ route('visits.updateStatus', $visit) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="diperiksa">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-user-md text-info me-2"></i>Diperiksa
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @if($visit->status != 'selesai')
                                                <li>
                                                    <form action="{{ route('visits.updateStatus', $visit) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <input type="hidden" name="status" value="selesai">
                                                        <button type="submit" class="dropdown-item">
                                                            <i class="fas fa-check-circle text-success me-2"></i>Selesai
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h5 class="mt-3 mb-2">Tidak ada data kunjungan</h5>
                    <p class="text-muted mb-4">Tidak ditemukan kunjungan yang sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('visits.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Kunjungan
                    </a>
                </div>
                @endif
            </div>
            @if($visits->count() > 0)
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $visits->firstItem() ?? 0 }}-{{ $visits->lastItem() ?? 0 }} dari {{ $visits->total() }} kunjungan
                    </div>
                    {{ $visits->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Live Clock for Doctor View
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const clockElement = document.getElementById('liveClock');
        if (clockElement) {
            clockElement.textContent = timeString;
        }
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    });
    
    // Start clock if in doctor view
    @if(auth()->user()->role == 'dokter')
    setInterval(updateClock, 1000);
    updateClock();
    
    // Auto-refresh for doctor's queue every 30 seconds
    setInterval(function() {
        location.reload();
    }, 30000);
    @endif
    
    // Auto-refresh for waiting list in admin view
    @if(auth()->user()->role != 'dokter' && request('status') == 'menunggu')
    setInterval(function() {
        location.reload();
    }, 30000);
    @endif
    
    // Form submission feedback
    document.querySelectorAll('form[action*="updateStatus"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
            }
        });
    });
</script>
@endsection