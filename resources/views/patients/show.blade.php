@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <!-- Patient Profile Card -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-injured me-2"></i>Profil Pasien
                </h5>
            </div>
            <div class="card-body">
                <!-- Patient Avatar and Basic Info -->
                <div class="text-center mb-4">
                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 80px; height: 80px; font-size: 32px;">
                        {{ strtoupper(substr($patient->nama, 0, 1)) }}
                    </div>
                    <h4 class="mb-1">{{ $patient->nama }}</h4>
                    <p class="text-muted mb-0">No. RM: <strong>{{ $patient->no_rekam_medis }}</strong></p>
                </div>
                
                <!-- Patient Details -->
                <div class="patient-details">
                    <div class="detail-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Usia</span>
                            <span class="font-weight-bold">{{ $patient->umur }} tahun</span>
                        </div>
                    </div>
                    
                    <div class="detail-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Jenis Kelamin</span>
                            <span class="font-weight-bold">
                                {{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Tanggal Lahir</span>
                            <span class="font-weight-bold">{{ $patient->tanggal_lahir->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="detail-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">No. HP</span>
                            <span class="font-weight-bold">{{ $patient->no_hp }}</span>
                        </div>
                    </div>
                    
                    <div class="detail-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Total Kunjungan</span>
                            <span class="badge bg-primary">{{ $patient->visits_count ?? $patient->visits()->count() }}</span>
                        </div>
                    </div>
                    
                    <div class="detail-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Terdaftar Sejak</span>
                            <span class="font-weight-bold">{{ $patient->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Address -->
                <div class="mt-4">
                    <h6 class="text-primary mb-2">Alamat</h6>
                    <p class="mb-0">{{ $patient->alamat }}</p>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit Data
                        </a>
                        <a href="{{ route('visits.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Kunjungan Baru
                        </a>
                        {{-- <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickActionsModal">
                            <i class="fas fa-bolt me-2"></i>Aksi Cepat
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Medical Summary -->
        <div class="card shadow mt-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-medical me-2"></i>Ringkasan Medis
                </h6>
            </div>
            <div class="card-body">
                @php
                    $lastVisit = $patient->visits()->latest()->first();
                    $commonDiagnosis = $patient->visits()
                        ->whereHas('medicalRecord')
                        ->with('medicalRecord')
                        ->get()
                        ->pluck('medicalRecord.diagnosa')
                        ->filter()
                        ->countBy()
                        ->sortDesc()
                        ->take(3);
                    
                    $lastPrescription = $patient->visits()
                        ->whereHas('medicalRecord.prescriptions')
                        ->with('medicalRecord.prescriptions.medicine')
                        ->latest()
                        ->first();
                @endphp
                
                @if($lastVisit && $lastVisit->medicalRecord)
                <div class="mb-3">
                    <h6 class="text-primary mb-2">Kunjungan Terakhir</h6>
                    <p class="mb-1">
                        <strong>Tanggal:</strong> {{ $lastVisit->created_at->format('d/m/Y') }}
                    </p>
                    <p class="mb-1">
                        <strong>Dokter:</strong> {{ $lastVisit->doctor->name }}
                    </p>
                    <p class="mb-1">
                        <strong>Diagnosa:</strong> {{ $lastVisit->medicalRecord->diagnosa }}
                    </p>
                </div>
                @endif
                
                @if($commonDiagnosis->count() > 0)
                <div class="mb-3">
                    <h6 class="text-primary mb-2">Diagnosa Umum</h6>
                    <div class="list-group list-group-flush">
                        @foreach($commonDiagnosis as $diagnosa => $count)
                        <div class="list-group-item px-0 py-1">
                            <div class="d-flex justify-content-between">
                                <span>{{ $diagnosa }}</span>
                                <span class="badge bg-info">{{ $count }}x</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if($lastPrescription && $lastPrescription->medicalRecord->prescriptions->count() > 0)
                <div>
                    <h6 class="text-primary mb-2">Resep Terakhir</h6>
                    <div class="list-group list-group-flush">
                        @foreach($lastPrescription->medicalRecord->prescriptions as $prescription)
                        <div class="list-group-item px-0 py-1">
                            <div class="d-flex justify-content-between">
                                <span>{{ $prescription->medicine->nama_obat }}</span>
                                <span class="badge bg-secondary">{{ $prescription->jumlah }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        <!-- Visit History -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Riwayat Kunjungan
                </h5>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="printMedicalHistory()">
                        <i class="fas fa-print me-1"></i>Cetak
                    </button>
                    <a href="{{ route('visits.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Visit Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body py-2 text-center">
                                <h6 class="mb-0">Total Kunjungan</h6>
                                <h4 class="mb-0">{{ $patient->visits()->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body py-2 text-center">
                                <h6 class="mb-0">Tahun Ini</h6>
                                <h4 class="mb-0">{{ $patient->visits()->whereYear('created_at', date('Y'))->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body py-2 text-center">
                                <h6 class="mb-0">Bulan Ini</h6>
                                <h4 class="mb-0">{{ $patient->visits()->whereMonth('created_at', date('m'))->count() }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body py-2 text-center">
                                <h6 class="mb-0">Hari Ini</h6>
                                <h4 class="mb-0">{{ $patient->visits()->whereDate('created_at', date('Y-m-d'))->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Visit List -->
                <div class="table-responsive">
                    <table class="table table-hover" id="visitsTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Dokter</th>
                                <th>Keluhan</th>
                                <th>Diagnosa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($visits as $visit)
                            <tr>
                                <td>
                                    <div>{{ $visit->tanggal_kunjungan->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $visit->created_at->format('H:i') }}</small>
                                </td>
                                <td>{{ $visit->doctor->name }}</td>
                                <td>
                                    @if($visit->medicalRecord)
                                        <small>{{ Str::limit($visit->medicalRecord->keluhan, 30) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($visit->medicalRecord)
                                        <span class="badge bg-info">{{ Str::limit($visit->medicalRecord->diagnosa, 20) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-status-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($visit->medicalRecord)
                                            <a href="{{ route('medical-records.show', $visit->medicalRecord) }}" 
                                               class="btn btn-info" title="Lihat Rekam Medis">
                                                <i class="fas fa-file-medical"></i>
                                            </a>
                                        @elseif(auth()->user()->role == 'dokter')
                                            <a href="{{ route('medical-records.create', $visit) }}" 
                                               class="btn btn-success" title="Input Rekam Medis">
                                                <i class="fas fa-stethoscope"></i>
                                            </a>
                                        @endif
                                        
                                        
                                        
                                        
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                                    <h6 class="text-muted">Belum ada riwayat kunjungan</h6>
                                    <p class="text-muted mb-0">Pasien belum pernah melakukan kunjungan</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($visits->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $visits->links() }}
                </div>
                @endif
            </div>
        </div>
        
        <!-- Medical History Timeline -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-timeline me-2"></i>Riwayat Medis (Timeline)
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($visits as $visit)
                    @if($visit->medicalRecord)
                    <div class="timeline-item mb-4">
                        <div class="timeline-marker {{ $visit->status == 'selesai' ? 'bg-success' : 'bg-warning' }}"></div>
                        <div class="timeline-content">
                            <div class="timeline-header d-flex justify-content-between">
                                <h6 class="mb-0">{{ $visit->doctor->name }}</h6>
                                <small class="text-muted">{{ $visit->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                            <div class="timeline-body mt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Keluhan:</strong></p>
                                        <p class="mb-3">{{ $visit->medicalRecord->keluhan }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Diagnosa:</strong></p>
                                        <p class="mb-3">{{ $visit->medicalRecord->diagnosa }}</p>
                                    </div>
                                </div>
                                
                                @if($visit->medicalRecord->tindakan)
                                <div class="row">
                                    <div class="col-12">
                                        <p class="mb-1"><strong>Tindakan:</strong></p>
                                        <p class="mb-3">{{ $visit->medicalRecord->tindakan }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($visit->medicalRecord->prescriptions->count() > 0)
                                <div class="row">
                                    <div class="col-12">
                                        <p class="mb-1"><strong>Resep:</strong></p>
                                        <div class="list-group list-group-flush">
                                            @foreach($visit->medicalRecord->prescriptions as $prescription)
                                            <div class="list-group-item px-0 py-1">
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ $prescription->medicine->nama_obat }}</span>
                                                    <span class="badge bg-secondary">{{ $prescription->jumlah }} × 
                                                        {{ $prescription->aturan_pakai }}</span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="timeline-footer mt-2">
                                <small class="text-muted">
                                    Status: 
                                    <span class="badge badge-status-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                
                @if($visits->filter(fn($visit) => $visit->medicalRecord)->count() == 0)

                <div class="text-center py-4">
                    <i class="fas fa-file-medical-alt fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Belum ada riwayat medis</h6>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Additional Information in Show Page -->
<div class="detail-item mb-3">
    <div class="d-flex justify-content-between">
        <span class="text-muted">NIK</span>
        <span class="font-weight-bold">{{ $patient->nik ?? '-' }}</span>
    </div>
</div>

<div class="detail-item mb-3">
    <div class="d-flex justify-content-between">
        <span class="text-muted">No. BPJS</span>
        <span class="font-weight-bold">{{ $patient->no_bpjs ?? '-' }}</span>
    </div>
</div>

<div class="detail-item mb-3">
    <div class="d-flex justify-content-between">
        <span class="text-muted">Golongan Darah</span>
        <span class="font-weight-bold">{{ $patient->golongan_darah ?? '-' }}</span>
    </div>
</div>

<div class="detail-item mb-3">
    <div class="d-flex justify-content-between">
        <span class="text-muted">Alergi</span>
        <span class="font-weight-bold">{{ $patient->alergi ?? 'Tidak ada' }}</span>
    </div>
</div>



<div class="detail-item mb-3">
    <div class="d-flex justify-content-between">
        <span class="text-muted">Status Pernikahan</span>
        <span class="font-weight-bold">{{ $patient->status_pernikahan ?? '-' }}</span>
    </div>
</div>

<div class="detail-item mb-3">
    <div class="d-flex justify-content-between">
        <span class="text-muted">Email</span>
        <span class="font-weight-bold">{{ $patient->email ?? '-' }}</span>
    </div>
</div>

<div class="detail-item mb-3">
    <div class="d-flex justify-content-between">
        <span class="text-muted">No. HP Keluarga</span>
        <span class="font-weight-bold">{{ $patient->no_hp_keluarga ?? '-' }}</span>
    </div>
</div>

@if($patient->nama_keluarga)
<div class="detail-item mb-3">
    <div class="d-flex justify-content-between">
        <span class="text-muted">Kontak Darurat</span>
        <span class="font-weight-bold">{{ $patient->nama_keluarga }} ({{ $patient->hubungan_keluarga }})</span>
    </div>
</div>
@endif

<!-- Quick Actions Modal -->
{{-- <div class="modal fade" id="quickActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Cepat - {{ $patient->nama }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <a href="{{ route('visits.create', ['patient_id' => $patient->id]) }}" 
                           class="btn btn-primary w-100 h-100 py-3">
                            <i class="fas fa-calendar-plus fa-2x mb-2"></i>
                            <br>
                            <span>Kunjungan Baru</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('patients.edit', $patient) }}" 
                           class="btn btn-warning w-100 h-100 py-3">
                            <i class="fas fa-edit fa-2x mb-2"></i>
                            <br>
                            <span>Edit Data</span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-info w-100 h-100 py-3" onclick="printPatientCard()">
                            <i class="fas fa-id-card fa-2x mb-2"></i>
                            <br>
                            <span>Cetak Kartu</span>
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-success w-100 h-100 py-3" onclick="sendReminder()">
                            <i class="fas fa-sms fa-2x mb-2"></i>
                            <br>
                            <span>Kirim SMS</span>
                        </button>
                    </div>
                </div>
                
                <!-- Quick Notes -->
                <div class="mt-4">
                    <label class="form-label">Catatan Cepat</label>
                    <textarea class="form-control" id="quickNote" rows="2" placeholder="Tambahkan catatan untuk pasien ini..."></textarea>
                    <button class="btn btn-outline-primary w-100 mt-2" onclick="saveQuickNote()">
                        <i class="fas fa-save me-2"></i>Simpan Catatan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> --}}

<!-- Visit Detail Modal -->
<div class="modal fade" id="visitDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="visitDetailContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-placeholder {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .detail-item {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 0.5rem;
    }
    
    .detail-item:last-child {
        border-bottom: none;
    }
    
    /* Timeline Styles */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
    }
    
    .timeline-marker {
        position: absolute;
        left: -23px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 3px solid white;
        z-index: 1;
    }
    
    .timeline-content {
        background: white;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }
    
    .timeline-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
    
    .timeline-body {
        font-size: 0.9rem;
    }
    
    .timeline-footer {
        border-top: 1px solid #e9ecef;
        padding-top: 10px;
        margin-top: 10px;
    }
    
    /* Print Styles */
    @media print {
        .sidebar, .navbar, .card-header button, .btn-group, .modal {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .card-header {
            background-color: white !important;
            color: black !important;
        }
        
        .main-content {
            margin-left: 0 !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Visit detail modal
        function showVisitDetail(visitId) {
            fetch(`/api/visits/${visitId}/detail`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('visitDetailContent').innerHTML = data.html;
                    const modal = new bootstrap.Modal(document.getElementById('visitDetailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error loading visit detail:', error);
                });
        }
        
        // Print medical history
        function printMedicalHistory() {
            // Clone the visit history table
            const originalTable = document.getElementById('visitsTable');
            const printWindow = window.open('', '_blank');
            
            printWindow.document.write(`
                <html>
                <head>
                    <title>Riwayat Kunjungan - {{ $patient->nama }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; }
                        .header { text-align: center; margin-bottom: 30px; }
                        .patient-info { margin-bottom: 20px; }
                        .print-date { text-align: right; margin-bottom: 20px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>KLINIK PRIMA MEDIKA</h2>
                        <h3>Riwayat Kunjungan Pasien</h3>
                    </div>
                    
                    <div class="patient-info">
                        <p><strong>Nama:</strong> {{ $patient->nama }}</p>
                        <p><strong>No. RM:</strong> {{ $patient->no_rekam_medis }}</p>
                        <p><strong>Usia:</strong> {{ $patient->umur }} tahun</p>
                        <p><strong>Jenis Kelamin:</strong> {{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    
                    <div class="print-date">
                        Dicetak pada: ${new Date().toLocaleDateString('id-ID', { 
                            day: 'numeric', 
                            month: 'long', 
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}
                    </div>
                    
                    ${originalTable.outerHTML}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.print();
        }
        
        // Print patient card
        function printPatientCard() {
            const printWindow = window.open('', '_blank');
            
            printWindow.document.write(`
                <html>
                <head>
                    <title>Kartu Pasien - {{ $patient->nama }}</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                        .patient-card { 
                            width: 85mm; 
                            height: 54mm; 
                            border: 2px solid #007bff; 
                            border-radius: 10px; 
                            padding: 15px;
                            position: relative;
                        }
                        .clinic-name { 
                            text-align: center; 
                            font-size: 14px; 
                            font-weight: bold; 
                            color: #007bff;
                            margin-bottom: 10px;
                        }
                        .patient-name { 
                            font-size: 18px; 
                            font-weight: bold; 
                            text-align: center;
                            margin: 10px 0;
                        }
                        .patient-details { 
                            font-size: 12px; 
                            margin-bottom: 5px;
                        }
                        .qr-code { 
                            position: absolute; 
                            bottom: 10px; 
                            right: 10px; 
                            width: 40px; 
                            height: 40px;
                            background: #f8f9fa;
                            border: 1px solid #ddd;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 8px;
                        }
                        .card-footer {
                            font-size: 10px;
                            text-align: center;
                            margin-top: 10px;
                            color: #666;
                        }
                    </style>
                </head>
                <body>
                    <div class="patient-card">
                        <div class="clinic-name">KLINIK PRIMA MEDIKA</div>
                        <div class="patient-name">{{ $patient->nama }}</div>
                        
                        <div class="patient-details">
                            <strong>No. RM:</strong> {{ $patient->no_rekam_medis }}
                        </div>
                        <div class="patient-details">
                            <strong>Tanggal Lahir:</strong> {{ $patient->tanggal_lahir->format('d/m/Y') }}
                        </div>
                        <div class="patient-details">
                            <strong>Jenis Kelamin:</strong> {{ $patient->jenis_kelamin == 'L' ? 'L' : 'P' }}
                        </div>
                        
                        <div class="qr-code">
                            QR Code
                        </div>
                        
                        <div class="card-footer">
                            Kartu ini untuk keperluan berobat di Klinik Prima Medika
                        </div>
                    </div>
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.print();
        }
        
        // Send SMS reminder
        function sendReminder() {
            const phoneNumber = '{{ $patient->no_hp }}';
            const message = `Halo {{ $patient->nama }}, ini dari Klinik Prima Medika.`;
            
            if (confirm(`Kirim SMS ke ${phoneNumber}?`)) {
                fetch('/api/patients/send-sms', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        phone: phoneNumber,
                        message: message
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('SMS berhasil dikirim!');
                    } else {
                        alert('Gagal mengirim SMS: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan saat mengirim SMS');
                });
            }
        }
        
        // Save quick note
        function saveQuickNote() {
            const note = document.getElementById('quickNote').value;
            
            if (!note.trim()) {
                alert('Masukkan catatan terlebih dahulu');
                return;
            }
            
            fetch('/api/patients/{{ $patient->id }}/notes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ note: note })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Catatan berhasil disimpan');
                    document.getElementById('quickNote').value = '';
                    bootstrap.Modal.getInstance(document.getElementById('quickActionsModal')).hide();
                }
            })
            .catch(error => {
                alert('Gagal menyimpan catatan');
            });
        }
        
        // Make functions global
        window.showVisitDetail = showVisitDetail;
        window.printMedicalHistory = printMedicalHistory;
        window.printPatientCard = printPatientCard;
        window.sendReminder = sendReminder;
        window.saveQuickNote = saveQuickNote;
    });
</script>
@endsection