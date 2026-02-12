@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('styles')
<style>
    /* Simplified Stat Cards */
    .doctor-stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .doctor-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        color: white;
        font-size: 1.25rem;
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-label {
        font-size: 12px;
        color: var(--text-secondary);
        font-weight: 500;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.2;
    }
    
    /* Patient Queue */
    .queue-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }
    
    .queue-item:hover {
        background: var(--background);
    }
    
    .queue-item:last-child {
        border-bottom: none;
    }
    
    .queue-number-badge {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: white;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .queue-number-primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #4f46e5 100%);
    }
    
    .queue-number-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }
    
    .queue-patient-info {
        flex: 1;
        min-width: 0;
    }
    
    .patient-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .patient-meta {
        font-size: 12px;
        color: var(--text-secondary);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .queue-status {
        flex-shrink: 0;
    }
    
    .queue-action {
        flex-shrink: 0;
    }
    
    /* Status Colors */
    .status-waiting {
        background-color: rgba(251, 191, 36, 0.1);
        color: #92400e;
        border-left: 4px solid #fbbf24;
    }
    
    .status-examining {
        background-color: rgba(59, 130, 246, 0.1);
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }
    
    .status-completed {
        background-color: rgba(16, 185, 129, 0.1);
        color: #065f46;
        border-left: 4px solid #10b981;
    }
    
    /* Medical Records */
    .record-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
    }
    
    .record-item:hover {
        background: var(--background);
    }
    
    .record-item:last-child {
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
        font-size: 13px;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    
    .record-diagnosis {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 2px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .record-time {
        font-size: 11px;
        color: var(--text-secondary);
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    
    .main-action {
        flex: 2;
        min-width: 200px;
    }
    
    .secondary-action {
        flex: 1;
        min-width: 120px;
    }
    
    /* Empty States */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }
    
    .empty-state-icon {
        font-size: 2.5rem;
        color: var(--border-color);
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .empty-state-text {
        color: var(--text-secondary);
        margin: 0;
    }
    
    /* Quick Actions */
    .quick-action-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    
    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 20px 12px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .quick-action-btn:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }
    
    .quick-action-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 14px;
    }
    
    .quick-action-label {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 11px;
        text-align: center;
    }
    
    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .main-action, .secondary-action {
            width: 100%;
        }
        
        .queue-item {
            flex-wrap: wrap;
        }
        
        .queue-status {
            width: 100%;
            margin-top: 8px;
        }
        
        .queue-action {
            width: 100%;
            margin-top: 8px;
        }
        
        .quick-action-grid {
            grid-template-columns: repeat(2, 1fr);
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
                <h1 class="page-title mb-1">Dashboard Dokter</h1>
                <p class="page-subtitle">Dr. {{ auth()->user()->name }} • {{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('visits.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-list-ul me-2"></i>Lihat Antrian
            </a>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            @php
                $nextPatient = $todayVisits->where('status', 'menunggu')->first();
            @endphp
            
            @if($nextPatient)
                <a href="{{ route('medical-records.create', $nextPatient) }}" 
                   class="btn btn-primary main-action">
                    <i class="fas fa-play me-2"></i>Mulai Pemeriksaan Pasien
                </a>
            @else
                <button class="btn btn-success main-action" disabled>
                    <i class="fas fa-check me-2"></i>Semua Pasien Selesai
                </button>
            @endif
            
            <a href="{{ route('visits.create') }}" class="btn btn-outline-primary secondary-action">
                <i class="fas fa-plus me-2"></i>Kunjungan Baru
            </a>
            
            {{-- <a href="{{ route('patients.create') }}" class="btn btn-outline-secondary secondary-action">
                <i class="fas fa-user-plus me-2"></i>Pasien Baru
            </a> --}}
        </div>

        <!-- Stats Overview -->
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="doctor-stat-card">
                    <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Menunggu</div>
                        <div class="stat-value">{{ $stats['waitingPatients'] }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="doctor-stat-card">
                    <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Selesai Hari Ini</div>
                        <div class="stat-value">{{ $stats['completedToday'] }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="doctor-stat-card">
                    <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Kunjungan</div>
                        <div class="stat-value">{{ $stats['todayAppointments'] }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="doctor-stat-card">
                    <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Rata-rata Rekam</div>
                        <div class="stat-value">{{ $stats['avgRecords'] ?? '0' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row g-3">
            <!-- Patient Queue -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Antrian Pasien Hari Ini</h5>
                            <small class="text-muted">{{ $todayVisits->count() }} pasien terdaftar</small>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="card-body p-0">
                        @if($todayVisits->count() > 0)
                            @foreach($todayVisits as $visit)
                            <div class="queue-item status-{{ $visit->status }}">
                                <div class="queue-number-badge {{ $visit->prioritas == 'prioritas' ? 'queue-number-danger' : 'queue-number-primary' }}">
                                    @if($visit->nomor_antrian)
                                        {{ $visit->prefix_antrian ?? 'A' }}-{{ str_pad($visit->nomor_antrian, 3, '0', STR_PAD_LEFT) }}
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                
                                <div class="queue-patient-info">
                                    <div class="patient-name">{{ $visit->patient->nama }}</div>
                                    <div class="patient-meta">
                                        <span>{{ $visit->patient->no_rekam_medis }}</span>
                                        <span>•</span>
                                        <span>{{ $visit->patient->umur }} tahun</span>
                                    </div>
                                </div>
                                
                                <div class="queue-status">
                                    <span class="badge badge-status-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </div>
                                
                                <div class="queue-action">
                                    @if($visit->status == 'menunggu')
                                        <form action="{{ route('visits.updateStatus', $visit) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="diperiksa">
                                            <button type="submit" class="btn btn-sm btn-success" title="Mulai Pemeriksaan">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @elseif($visit->status == 'diperiksa')
                                        <a href="{{ route('medical-records.create', $visit) }}" 
                                           class="btn btn-sm btn-primary" title="Input Rekam Medis">
                                            <i class="fas fa-file-medical"></i>
                                        </a>
                                    @else
                                        {{-- <a href="{{ route('patients.show', $visit->patient) }}" 
                                           class="btn btn-sm btn-outline-secondary" title="Lihat Pasien">
                                            <i class="fas fa-eye"></i>
                                        </a> --}}
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-calendar-times empty-state-icon"></i>
                                <p class="empty-state-text">Tidak ada kunjungan hari ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="col-lg-4">
                <!-- Quick Actions -->
                {{-- <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-body">
                        <div class="quick-action-grid">
                            <a href="{{ route('medicines.index') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                    <i class="fas fa-pills"></i>
                                </div>
                                <span class="quick-action-label">Data Obat</span>
                            </a>
                            
                            <a href="{{ route('medical-records.index') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <i class="fas fa-file-medical-alt"></i>
                                </div>
                                <span class="quick-action-label">Rekam Medis</span>
                            </a>
                            
                            <a href="{{ route('patients.index') }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                                    <i class="fas fa-user-injured"></i>
                                </div>
                                <span class="quick-action-label">Data Pasien</span>
                            </a>
                            
                            <a href="{{ route('reports.index', ['type' => 'visits']) }}" class="quick-action-btn">
                                <div class="quick-action-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <span class="quick-action-label">Laporan</span>
                            </a>
                        </div>
                        
                        @if($nextPatient)
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                Pasien berikutnya: <strong>{{ $nextPatient->patient->nama }}</strong>
                            </small>
                        </div>
                        @endif
                    </div>
                </div> --}}

                <!-- Recent Medical Records -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Rekam Medis Terbaru</h5>
                        <small class="text-muted">Riwayat pemeriksaan</small>
                    </div>
                    <div class="card-body p-0">
                        @if($recentRecords->count() > 0)
                            @foreach($recentRecords as $record)
                            <div class="record-item">
                                <div class="record-icon">
                                    <i class="fas fa-file-medical-alt"></i>
                                </div>
                                <div class="record-info">
                                    <div class="record-patient">{{ $record->visit->patient->nama }}</div>
                                    <div class="record-diagnosis">{{ Str::limit($record->diagnosa, 40) }}</div>
                                    <div class="record-time">{{ $record->created_at->diffForHumans() }}</div>
                                </div>
                                <a href="{{ route('medical-records.show', $record->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                            @endforeach
                            
                            <div class="text-center p-3">
                                <a href="{{ route('medical-records.index') }}" class="text-primary">
                                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-file-medical empty-state-icon"></i>
                                <p class="empty-state-text">Belum ada rekam medis</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh every 60 seconds
    setTimeout(function() {
        location.reload();
    }, 60000);
</script>
@endsection