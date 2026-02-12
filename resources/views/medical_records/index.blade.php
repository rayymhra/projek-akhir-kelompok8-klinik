@extends('layouts.app')

@section('title', 'Rekam Medis')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-file-medical text-primary"></i> Rekam Medis</h2>
            <p class="text-muted">Daftar rekam medis pasien</p>
        </div>
        {{-- <div class="col-md-6 text-end">
            @if(auth()->user()->role == 'dokter')
            <a href="{{ route('medical-records.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Rekam Medis Baru
            </a>
            @endif
        </div> --}}
    </div>

    <!-- Filter Section -->
    {{-- <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('medical-records.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Cari nama pasien atau No. RM..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="doctor_id" class="form-select">
                            <option value="">Semua Dokter</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div> --}}

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Total Rekam Medis</h6>
                            <h3 class="mb-0">{{ $totalRecords }}</h3>
                        </div>
                        <div class="icon-circle bg-primary-light">
                            <i class="fas fa-file-medical text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Hari Ini</h6>
                            <h3 class="mb-0">{{ $todayRecords }}</h3>
                        </div>
                        <div class="icon-circle bg-success-light">
                            <i class="fas fa-calendar-day text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Minggu Ini</h6>
                            <h3 class="mb-0">{{ $weekRecords }}</h3>
                        </div>
                        <div class="icon-circle bg-info-light">
                            <i class="fas fa-calendar-week text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">Dokter Aktif</h6>
                            <h3 class="mb-0">{{ $activeDoctors }}</h3>
                        </div>
                        <div class="icon-circle bg-warning-light">
                            <i class="fas fa-user-md text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical Records Table -->
    <div class="card shadow-sm">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i> Daftar Rekam Medis
                    <span class="badge bg-primary ms-2">{{ $medicalRecords->total() }}</span>
                </h5>
                <div class="text-muted">
                    Menampilkan {{ $medicalRecords->firstItem() }} - {{ $medicalRecords->lastItem() }} dari {{ $medicalRecords->total() }}
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($medicalRecords->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Diagnosa</th>
                                <th>Tindakan</th>
                                <th>Tanggal</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicalRecords as $index => $record)
                            <tr>
                                <td>{{ $medicalRecords->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-circle bg-secondary">
                                                {{ substr($record->visit->patient->nama, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <strong>{{ $record->visit->patient->nama }}</strong>
                                            <div class="small text-muted">
                                                {{ $record->visit->patient->no_rekam_medis }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-md text-primary me-2"></i>
                                        <span>{{ $record->visit->doctor->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="diagnosa-preview">
                                        {{ Str::limit($record->diagnosa, 50) }}
                                        @if(strlen($record->diagnosa) > 50)
                                            <a href="#" class="text-primary small" data-bs-toggle="tooltip" 
                                               title="{{ $record->diagnosa }}">
                                                ...lihat lebih
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($record->tindakan)
                                        <span class="badge bg-info">{{ Str::limit($record->tindakan, 30) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-nowrap">
                                        {{ $record->created_at->format('d/m/Y') }}
                                        <div class="small text-muted">{{ $record->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('medical-records.show', $record) }}" 
                                           class="btn btn-outline-primary" 
                                           data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- @if(auth()->user()->role == 'dokter')
                                        <a href="{{ route('medical-records.edit', $record) }}" 
                                           class="btn btn-outline-warning"
                                           data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif --}}
                                        <button type="button" class="btn btn-outline-secondary"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#printModal{{ $record->id }}"
                                                data-bs-toggle="tooltip" title="Cetak">
                                            <i class="fas fa-print"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Print Modal -->
                            <div class="modal fade" id="printModal{{ $record->id }}" tabindex="-1" 
                                 aria-labelledby="printModalLabel{{ $record->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="printModalLabel{{ $record->id }}">
                                                <i class="fas fa-print me-2"></i> Cetak Rekam Medis
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Pilih format cetakan:</p>
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('medical-records.print', $record) }}" 
                                                   target="_blank" class="btn btn-primary">
                                                    <i class="fas fa-file-pdf me-2"></i> Format PDF
                                                </a>
                                                <a href="{{ route('medical-records.print-simple', $record) }}" 
                                                   target="_blank" class="btn btn-outline-primary">
                                                    <i class="fas fa-print me-2"></i> Format Sederhana
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $medicalRecords->firstItem() }} - {{ $medicalRecords->lastItem() }} dari {{ $medicalRecords->total() }} rekam medis
                    </div>
                    <div>
                        {{ $medicalRecords->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-file-medical-alt fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">Tidak ada rekam medis</h4>
                    <div class="col-md-6 text-end">
    <span class="text-muted small">
        Pembuatan rekam medis dilakukan dari dashboard dokter
    </span>
</div>

                    {{-- @if(auth()->user()->role == 'dokter')
                        <a href="{{ route('medical-records.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Buat Rekam Medis Pertama
                        </a>
                    @endif --}}
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Access -->
    {{-- <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Rekam Medis Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentRecords as $recent)
                        <a href="{{ route('medical-records.show', $recent) }}" 
                           class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <div>
                                    <strong>{{ $recent->visit->patient->nama }}</strong>
                                    <div class="small text-muted">{{ Str::limit($recent->diagnosa, 40) }}</div>
                                </div>
                                <small class="text-muted">{{ $recent->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-3 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">Belum ada rekam medis</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center p-3 border rounded">
                                <h3 class="text-primary mb-1">{{ $recordsByDoctor->count() }}</h3>
                                <small class="text-muted">Dokter Aktif</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center p-3 border rounded">
                                <h3 class="text-success mb-1">{{ $avgRecordsPerDay }}</h3>
                                <small class="text-muted">Rata-rata/hari</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6>Top Diagnosa</h6>
                            <div class="list-group list-group-flush">
                                @foreach($topDiagnoses as $diagnosis)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between">
                                        <span>{{ Str::limit($diagnosis, 30) }}</span>
                                        <span class="badge bg-primary">{{ $diagnosisCounts[$loop->index] ?? 0 }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection

@section('styles')
<style>
    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 14px;
    }
    
    .avatar-sm {
        display: inline-flex;
    }
    
    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    
    .bg-primary-light {
        background-color: rgba(99, 102, 241, 0.1);
    }
    
    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
    }
    
    .bg-info-light {
        background-color: rgba(6, 182, 212, 0.1);
    }
    
    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.1);
    }
    
    .stat-card {
        border-left: 4px solid var(--primary-color);
        transition: transform 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    .list-group-item {
        border: none;
        padding: 12px 0;
        border-bottom: 1px solid var(--border-color);
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .diagnosa-preview {
        max-width: 250px;
        word-wrap: break-word;
    }
    
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.05);
    }
</style>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Auto refresh stats every 60 seconds
        setInterval(function() {
            fetch('/api/medical-records/stats')
                .then(response => response.json())
                .then(data => {
                    // Update stats cards
                    document.querySelector('.stat-card:nth-child(1) h3').textContent = data.total;
                    document.querySelector('.stat-card:nth-child(2) h3').textContent = data.today;
                    document.querySelector('.stat-card:nth-child(3) h3').textContent = data.week;
                })
                .catch(error => console.error('Error fetching stats:', error));
        }, 60000);
        
        // Quick filter by date
        const dateFilter = document.querySelector('input[name="date"]');
        if (dateFilter) {
            dateFilter.addEventListener('change', function() {
                if (this.value) {
                    this.closest('form').submit();
                }
            });
        }
    });
</script>
@endsection