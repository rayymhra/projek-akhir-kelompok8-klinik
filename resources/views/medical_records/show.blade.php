@extends('layouts.app')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-medical me-2"></i>Detail Rekam Medis
                </h5>
                <div class="btn-group">
                    @if(auth()->user()->role == 'dokter' && $medicalRecord->visit->doctor_id == auth()->id())
                        <a href="{{ route('medical-records.edit', $medicalRecord) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    @endif
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="fas fa-print me-2"></i>Cetak
                    </button>
                    <a href="{{ route('medical-records.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Header with Clinic Info (for printing) -->
                <div class="print-header d-none">
                    <div class="text-center mb-4">
                        <h2 class="mb-1">KLINIK PRIMA MEDIKA</h2>
                        <p class="mb-0">Jl. Kesehatan No. 123, Jakarta</p>
                        <p>Telp: (021) 123456 | Email: info@primamedika.com</p>
                        <hr>
                        <h3 class="text-primary">REKAM MEDIS</h3>
                    </div>
                </div>

                <!-- Patient Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Informasi Pasien</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="180">Nama Pasien:</th>
                                                <td>{{ $medicalRecord->visit->patient->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. Rekam Medis:</th>
                                                <td>{{ $medicalRecord->visit->patient->no_rekam_medis }}</td>
                                            </tr>
                                            <tr>
                                                <th>Usia:</th>
                                                <td>{{ $medicalRecord->visit->patient->umur }} tahun</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kelamin:</th>
                                                <td>{{ $medicalRecord->visit->patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Lahir:</th>
                                                <td>{{ $medicalRecord->visit->patient->tanggal_lahir->format('d/m/Y') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="180">Alamat:</th>
                                                <td>{{ $medicalRecord->visit->patient->alamat }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. HP:</th>
                                                <td>{{ $medicalRecord->visit->patient->no_hp }}</td>
                                            </tr>
                                            <tr>
                                                <th>Golongan Darah:</th>
                                                <td>{{ $medicalRecord->visit->patient->golongan_darah ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alergi:</th>
                                                <td>{{ $medicalRecord->visit->patient->alergi ?? 'Tidak ada' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Riwayat Penyakit:</th>
                                                <td>{{ $medicalRecord->visit->patient->riwayat_penyakit ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Informasi Kunjungan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Tanggal Kunjungan:</strong></p>
                                        <p>{{ $medicalRecord->visit->tanggal_kunjungan->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Waktu:</strong></p>
                                        <p>{{ $medicalRecord->created_at->format('H:i') }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Dokter:</strong></p>
                                        <p>{{ $medicalRecord->visit->doctor->name }}</p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="mb-1"><strong>Status:</strong></p>
                                        <span class="badge badge-status-selesai">Selesai</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Record Details -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="fas fa-file-medical-alt me-2"></i>Hasil Pemeriksaan</h6>
                            </div>
                            <div class="card-body">
                                <!-- Vital Signs -->
                                @if($medicalRecord->tekanan_darah || $medicalRecord->nadi || $medicalRecord->suhu || $medicalRecord->pernafasan)
                                <div class="mb-4">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-heartbeat me-2"></i>Tanda Vital
                                    </h6>
                                    <div class="row">
                                        @if($medicalRecord->tekanan_darah)
                                        <div class="col-md-3 mb-2">
                                            <strong>Tekanan Darah:</strong>
                                            <p class="mb-0">{{ $medicalRecord->tekanan_darah }}</p>
                                        </div>
                                        @endif
                                        @if($medicalRecord->nadi)
                                        <div class="col-md-3 mb-2">
                                            <strong>Nadi:</strong>
                                            <p class="mb-0">{{ $medicalRecord->nadi }}</p>
                                        </div>
                                        @endif
                                        @if($medicalRecord->suhu)
                                        <div class="col-md-3 mb-2">
                                            <strong>Suhu Tubuh:</strong>
                                            <p class="mb-0">{{ $medicalRecord->suhu }}</p>
                                        </div>
                                        @endif
                                        @if($medicalRecord->pernafasan)
                                        <div class="col-md-3 mb-2">
                                            <strong>Pernafasan:</strong>
                                            <p class="mb-0">{{ $medicalRecord->pernafasan }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                <!-- Keluhan -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-2">
                                        <i class="fas fa-comment-medical me-2"></i>Keluhan Pasien
                                    </h6>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($medicalRecord->keluhan)) !!}
                                    </div>
                                </div>

                                <!-- Pemeriksaan Fisik -->
                                @if($medicalRecord->pemeriksaan_fisik)
                                <div class="mb-4">
                                    <h6 class="text-primary mb-2">
                                        <i class="fas fa-stethoscope me-2"></i>Hasil Pemeriksaan Fisik
                                    </h6>
                                    <div class="border rounded p-3 bg-light">
                                        {!! nl2br(e($medicalRecord->pemeriksaan_fisik)) !!}
                                    </div>
                                </div>
                                @endif

                                <!-- Diagnosa -->
                                <div class="mb-4">
                                    <h6 class="text-danger mb-2">
                                        <i class="fas fa-diagnoses me-2"></i>Diagnosa
                                    </h6>
                                    <div class="border border-danger rounded p-3 bg-danger bg-opacity-10">
                                        {!! nl2br(e($medicalRecord->diagnosa)) !!}
                                    </div>
                                </div>

                                <!-- Tindakan -->
                                @if($medicalRecord->tindakan)
                                <div class="mb-4">
                                    <h6 class="text-warning mb-2">
                                        <i class="fas fa-procedures me-2"></i>Tindakan yang Dilakukan
                                    </h6>
                                    <div class="border border-warning rounded p-3 bg-warning bg-opacity-10">
                                        {!! nl2br(e($medicalRecord->tindakan)) !!}
                                    </div>
                                </div>
                                @endif

                                <!-- Catatan -->
                                @if($medicalRecord->catatan)
                                <div class="mb-4">
                                    <h6 class="text-info mb-2">
                                        <i class="fas fa-notes-medical me-2"></i>Catatan Tambahan
                                    </h6>
                                    <div class="border border-info rounded p-3 bg-info bg-opacity-10">
                                        {!! nl2br(e($medicalRecord->catatan)) !!}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prescriptions -->
                @if($medicalRecord->prescriptions->count() > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-prescription me-2"></i>Resep Obat
                                    <span class="badge bg-light text-dark ms-2">
                                        {{ $medicalRecord->prescriptions->count() }} item
                                    </span>
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Obat</th>
                                                <th>Kode</th>
                                                <th>Jumlah</th>
                                                <th>Aturan Pakai</th>
                                                @if(auth()->user()->role == 'admin')
                                                <th>Harga Satuan</th>
                                                <th>Subtotal</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($medicalRecord->prescriptions as $index => $prescription)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $prescription->medicine->nama_obat }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $prescription->medicine->jenis_obat }}</small>
                                                </td>
                                                <td>{{ $prescription->medicine->kode_obat }}</td>
                                                <td>{{ $prescription->jumlah }} {{ $prescription->medicine->satuan }}</td>
                                                <td>{{ $prescription->aturan_pakai }}</td>
                                                @if(auth()->user()->role == 'admin')
                                                <td>Rp {{ number_format($prescription->medicine->harga, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($prescription->jumlah * $prescription->medicine->harga, 0, ',', '.') }}</td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Footer Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-user-md me-2"></i>Dokter Pemeriksa
                                </h6>
                                <div class="text-center">
                                    <p class="mb-1">{{ $medicalRecord->visit->doctor->name }}</p>
                                    <p class="text-muted mb-0">Dokter Umum</p>
                                    <hr class="my-2">
                                    <p class="mb-0">
                                        <small class="text-muted">
                                            Ditandatangani secara digital
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dokumen
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <th width="150">ID Rekam Medis:</th>
                                        <td>RM-{{ str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Pada:</th>
                                        <td>{{ $medicalRecord->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Diperbarui Pada:</th>
                                        <td>{{ $medicalRecord->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>No. Kunjungan:</th>
                                        <td>V-{{ str_pad($medicalRecord->visit->id, 6, '0', STR_PAD_LEFT) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .print-header {
        display: none;
    }
    
    @media print {
        body * {
            visibility: hidden;
        }
        
        .card, .card * {
            visibility: visible;
        }
        
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none !important;
            box-shadow: none !important;
        }
        
        .print-header {
            display: block !important;
        }
        
        .btn-group, .btn {
            display: none !important;
        }
        
        .card-header {
            background-color: white !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
        }
        
        .badge {
            border: 1px solid #000;
        }
        
        .table {
            border: 1px solid #000;
        }
        
        .table th {
            background-color: #f8f9fa !important;
        }
    }
    
    .badge-status-selesai {
        background-color: #28a745;
        color: #fff;
    }
    
    .bg-danger.bg-opacity-10 {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    
    .bg-warning.bg-opacity-10 {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .bg-info.bg-opacity-10 {
        background-color: rgba(23, 162, 184, 0.1) !important;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add copy to clipboard functionality for medical record ID
        const medicalRecordId = 'RM-' + String({{ $medicalRecord->id }}).padStart(6, '0');
        
        // Create copy button dynamically
        const infoCard = document.querySelector('.card:last-child .card-body');
        if (infoCard) {
            const copyBtn = document.createElement('button');
            copyBtn.className = 'btn btn-sm btn-outline-primary mt-2';
            copyBtn.innerHTML = '<i class="fas fa-copy me-1"></i> Salin ID';
            copyBtn.onclick = function() {
                navigator.clipboard.writeText(medicalRecordId).then(function() {
                    const originalHtml = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="fas fa-check me-1"></i> Tersalin!';
                    copyBtn.className = 'btn btn-sm btn-success mt-2';
                    
                    setTimeout(function() {
                        copyBtn.innerHTML = originalHtml;
                        copyBtn.className = 'btn btn-sm btn-outline-primary mt-2';
                    }, 2000);
                });
            };
            infoCard.appendChild(copyBtn);
        }
        
        // Enhance print functionality
        document.querySelector('button[onclick="window.print()"]').addEventListener('click', function() {
            // Add clinic logo/info before printing
            const printHeader = document.querySelector('.print-header');
            printHeader.classList.remove('d-none');
            
            setTimeout(function() {
                window.print();
                printHeader.classList.add('d-none');
            }, 100);
        });
    });
</script>
@endsection