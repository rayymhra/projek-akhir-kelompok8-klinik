@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="dashboard-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard Dokter</h1>
            <p class="page-subtitle">Dr. {{ auth()->user()->name }} • {{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            @php
                $nextPatient = $todayVisits->where('status', 'menunggu')->first();
            @endphp
            @if($nextPatient)
                <a href="{{ route('medical-records.create', $nextPatient) }}" class="btn btn-primary">
                    <i class="fas fa-play me-2"></i>Mulai Pemeriksaan
                </a>
            @endif
            <a href="{{ route('visits.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list-ul me-2"></i>Lihat Antrian
            </a>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-waiting">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Pasien Menunggu</div>
                <div class="stat-value">{{ $stats['waitingPatients'] }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-warning">
                        <i class="fas fa-clock me-1"></i>Dalam Antrian
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Diperiksa Hari Ini</div>
                <div class="stat-value">{{ $stats['completedToday'] }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-success">
                        <i class="fas fa-check-circle me-1"></i>Selesai
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Kunjungan</div>
                <div class="stat-value">{{ $stats['todayAppointments'] }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-info">
                        <i class="fas fa-calendar-alt me-1"></i>Hari Ini
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <i class="fas fa-file-medical"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Rata-rata Rekam Medis</div>
                <div class="stat-value">{{ $stats['avgRecords'] ?? '0' }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-primary">
                        <i class="fas fa-chart-line me-1"></i>Per Hari
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Today's Queue -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Antrian Hari Ini</h5>
                    <small class="text-muted">{{ $todayVisits->count() }} pasien terdaftar</small>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                    <a href="{{ route('visits.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Kunjungan Baru
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="queue-list">
                    @forelse($todayVisits as $visit)
                    <div class="queue-item queue-status-{{ $visit->status }}">
                        <div class="queue-number">
                            @if($visit->nomor_antrian)
                                <span class="badge {{ $visit->prioritas == 'prioritas' ? 'bg-danger' : 'bg-primary' }}">
                                    {{ $visit->prefix_antrian ?? 'A' }}-{{ str_pad($visit->nomor_antrian, 3, '0', STR_PAD_LEFT) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">{{ $loop->iteration }}</span>
                            @endif
                        </div>
                        
                        <div class="queue-patient">
                            <div class="patient-avatar">
                                {{ substr($visit->patient->nama, 0, 1) }}
                            </div>
                            <div class="patient-info">
                                <div class="patient-name">{{ $visit->patient->nama }}</div>
                                <div class="patient-meta">
                                    <span>{{ $visit->patient->no_rekam_medis }}</span>
                                    <span>•</span>
                                    <span>{{ $visit->patient->umur }} thn</span>
                                    <span>•</span>
                                    <span>{{ $visit->patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="queue-time">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $visit->created_at->format('H:i') }}
                            </small>
                        </div>
                        
                        <div class="queue-status">
                            <span class="badge badge-status-{{ $visit->status }}">
                                {{ ucfirst($visit->status) }}
                            </span>
                        </div>
                        
                        <div class="queue-actions">
                            <div class="btn-group">
                                <a href="{{ route('patients.show', $visit->patient) }}" 
                                   class="btn btn-sm btn-outline-secondary" 
                                   title="Detail Pasien">
                                    <i class="fas fa-user"></i>
                                </a>
                                
                                @if($visit->status == 'menunggu')
                                    <form action="{{ route('visits.updateStatus', $visit) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="diperiksa">
                                        <button type="submit" class="btn btn-sm btn-success" title="Mulai Periksa">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                @if($visit->status == 'diperiksa')
                                    <a href="{{ route('medical-records.create', $visit) }}" 
                                       class="btn btn-sm btn-primary" title="Input Rekam Medis">
                                        <i class="fas fa-file-medical"></i>
                                    </a>
                                @endif
                                
                                @if($visit->status == 'selesai' && $visit->medicalRecord)
                                    <a href="{{ route('medical-records.show', $visit->medicalRecord->id) }}" 
                                       class="btn btn-sm btn-info" title="Lihat Rekam Medis">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada kunjungan hari ini</h5>
                        <p class="text-muted">Belum ada pasien yang terdaftar</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Side Panel -->
    <div class="col-lg-4">
        <!-- Quick Panel -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Panel Cepat</h5>
                <small class="text-muted">Aksi cepat dokter</small>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($nextPatient)
                        <a href="{{ route('medical-records.create', $nextPatient) }}" 
                           class="btn btn-lg btn-primary">
                            <i class="fas fa-play me-2"></i>Periksa Pasien Berikutnya
                        </a>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                {{ $nextPatient->patient->nama }} 
                                @if($nextPatient->nomor_antrian)
                                    ({{ $nextPatient->prefix_antrian ?? 'A' }}-{{ str_pad($nextPatient->nomor_antrian, 3, '0', STR_PAD_LEFT) }})
                                @endif
                            </small>
                        </div>
                    @else
                        <button class="btn btn-lg btn-outline-secondary" disabled>
                            <i class="fas fa-check me-2"></i>Semua Pasien Telah Dilayani
                        </button>
                    @endif
                    
                    <a href="{{ route('medicines.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-pills me-2"></i>Data Obat
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Recent Records -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Rekam Medis Terbaru</h5>
                <small class="text-muted">Riwayat pemeriksaan</small>
            </div>
            <div class="card-body p-0">
                <div class="recent-records-list">
                    @forelse($recentRecords as $record)
                    <div class="recent-record-item">
                        <div class="record-icon">
                            <i class="fas fa-file-medical-alt"></i>
                        </div>
                        <div class="record-info">
                            <div class="record-patient">{{ $record->visit->patient->nama }}</div>
                            <div class="record-diagnosis">{{ Str::limit($record->diagnosa, 40) }}</div>
                            <div class="record-meta">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $record->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        <div class="record-action">
                            <a href="{{ route('medical-records.show', $record->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state-small">
                        <i class="fas fa-file-medical fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Belum ada rekam medis</p>
                    </div>
                    @endforelse
                </div>
                
                @if($recentRecords->count() > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('medical-records.index') }}" class="text-primary">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stat-card-waiting {
        animation: pulse-warning 2s infinite;
    }
    
    @keyframes pulse-warning {
        0%, 100% {
            background: white;
        }
        50% {
            background: rgba(251, 191, 36, 0.05);
        }
    }
    
    /* Queue List */
    .queue-list {
        display: flex;
        flex-direction: column;
    }
    
    .queue-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }
    
    .queue-item:hover {
        background: var(--background);
    }
    
    .queue-item:last-child {
        border-bottom: none;
    }
    
    .queue-status-menunggu {
        border-left: 3px solid #fbbf24;
    }
    
    .queue-status-diperiksa {
        border-left: 3px solid #3b82f6;
        background: rgba(59, 130, 246, 0.03);
    }
    
    .queue-status-selesai {
        border-left: 3px solid #10b981;
        background: rgba(16, 185, 129, 0.03);
    }
    
    .queue-number {
        flex-shrink: 0;
    }
    
    .queue-patient {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }
    
    .patient-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        flex-shrink: 0;
    }
    
    .patient-info {
        min-width: 0;
        flex: 1;
    }
    
    .patient-name {
        font-weight: 600;
        font-size: 15px;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .patient-meta {
        font-size: 12px;
        color: var(--text-secondary);
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    
    .queue-time {
        flex-shrink: 0;
    }
    
    .queue-status {
        flex-shrink: 0;
        min-width: 90px;
    }
    
    .queue-actions {
        flex-shrink: 0;
    }
    
    /* Recent Records */
    .recent-records-list {
        display: flex;
        flex-direction: column;
    }
    
    .recent-record-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }
    
    .recent-record-item:hover {
        background: var(--background);
    }
    
    .recent-record-item:last-child {
        border-bottom: none;
    }
    
    .record-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .record-info {
        flex: 1;
        min-width: 0;
    }
    
    .record-patient {
        font-weight: 600;
        font-size: 14px;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .record-diagnosis {
        font-size: 13px;
        color: var(--text-secondary);
        margin-bottom: 4px;
    }
    
    .record-meta {
        font-size: 12px;
    }
    
    .record-action {
        flex-shrink: 0;
    }
    
    /* Empty States */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    
    .empty-state-small {
        padding: 40px 20px;
        text-align: center;
    }
    
    @media (max-width: 768px) {
        .queue-item {
            flex-wrap: wrap;
        }
        
        .queue-actions {
            width: 100%;
            margin-top: 8px;
        }
        
        .queue-actions .btn-group {
            width: 100%;
        }
        
        .queue-actions .btn-group .btn {
            flex: 1;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Auto-refresh every 60 seconds
    setTimeout(function() {
        location.reload();
    }, 60000);
</script>
@endsection