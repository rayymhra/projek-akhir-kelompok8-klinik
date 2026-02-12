@extends('layouts.app')

@section('title', 'Pilih Kunjungan - Buat Transaksi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-cash-register text-primary"></i> Buat Transaksi Baru</h2>
            <p class="text-muted">Pilih kunjungan pasien untuk membuat transaksi baru</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Transaksi
            </a>
        </div>
    </div>
    
    
    <!-- Info Box -->
    <div class="alert alert-info mb-4">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-info-circle fa-2x"></i>
            </div>
            <div>
                <h5 class="alert-heading">Informasi</h5>
                <p class="mb-0">Hanya menampilkan kunjungan yang <strong>sudah selesai</strong> dan <strong>belum memiliki transaksi</strong>. Pilih kunjungan untuk melanjutkan ke tahap berikutnya.</p>
            </div>
        </div>
    </div>
    
    <!-- Daftar Kunjungan -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i> Daftar Kunjungan Tersedia
                <span class="badge bg-light text-primary ms-2">{{ $visits->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($visits->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Pasien</th>
                                <th>No. RM</th>
                                <th>Dokter</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visits as $index => $visit)
                            <tr>
                                <td>{{ $visits->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $visit->patient->nama }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $visit->patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, 
                                        {{ $visit->patient->usia }} tahun
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $visit->patient->no_rekam_medis }}</span>
                                </td>
                                <td>{{ $visit->doctor->name }}</td>
                                <td>
                                    {{ $visit->tanggal_kunjungan->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">{{ $visit->tanggal_kunjungan->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check"></i> Selesai
                                    </span>
                                    @if($visit->medicalRecord)
                                    <br>
                                    <small class="text-success">
                                        <i class="fas fa-file-prescription"></i> Ada Resep
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('transactions.step2', $visit) }}" 
                                       class="btn btn-sm btn-success w-100">
                                        <i class="fas fa-arrow-right me-1"></i> Pilih
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $visits->firstItem() }} - {{ $visits->lastItem() }} dari {{ $visits->total() }} kunjungan
                    </div>
                    <div>
                        {{ $visits->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">Tidak ada kunjungan tersedia</h4>
                    <p class="text-muted">Semua kunjungan sudah memiliki transaksi atau belum selesai.</p>
                    {{-- <a href="{{ route('visits.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-external-link-alt me-2"></i> Lihat Semua Kunjungan
                    </a> --}}
                </div>
            @endif
        </div>
    </div>
    
    <!-- Step Indicator -->
    <div class="mt-4">
        <div class="d-flex justify-content-center">
            <div class="steps">
                <div class="step active">
                    <div class="step-circle">1</div>
                    <div class="step-label">Pilih Kunjungan</div>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-circle">2</div>
                    <div class="step-label">Tambah Item</div>
                </div>
                <div class="step-line"></div>
                <div class="step">
                    <div class="step-circle">3</div>
                    <div class="step-label">Pembayaran</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .steps {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .step {
        text-align: center;
        position: relative;
        min-width: 120px;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 10px;
        transition: all 0.3s;
    }
    
    .step.active .step-circle {
        background-color: #0d6efd;
        color: white;
    }
    
    .step-label {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .step.active .step-label {
        color: #0d6efd;
        font-weight: bold;
    }
    
    .step-line {
        width: 100px;
        height: 3px;
        background-color: #e9ecef;
        margin: 0 10px;
    }
    
    .table th {
        font-weight: 600;
        border-top: none;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection