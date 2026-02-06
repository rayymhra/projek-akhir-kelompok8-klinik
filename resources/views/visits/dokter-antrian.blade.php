@extends('layouts.app')

@section('title', 'Antrian Pasien Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list-ol me-2"></i> Antrian Pasien - {{ auth()->user()->name }}
                        </h5>
                        <span class="badge bg-light text-dark">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Stats -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Menunggu</h6>
                                            <h4 class="mb-0">{{ $visits->where('status', 'menunggu')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-clock fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Diperiksa</h6>
                                            <h4 class="mb-0">{{ $visits->where('status', 'diperiksa')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-user-md fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body py-3">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">Selesai</h6>
                                            <h4 class="mb-0">{{ $visits->where('status', 'selesai')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Patient -->
                    @php
                        $currentPatient = $visits->where('status', 'diperiksa')->first();
                    @endphp
                    @if($currentPatient)
                    <div class="alert alert-info alert-dismissible fade show">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-md fa-3x me-3"></i>
                            <div>
                                <h4 class="mb-1">Pasien Saat Ini</h4>
                                <h5 class="mb-1">{{ $currentPatient->patient->nama }}</h5>
                                <p class="mb-0">
                                    No. RM: {{ $currentPatient->patient->no_rekam_medis }} | 
                                    Umur: {{ $currentPatient->patient->umur }} tahun
                                </p>
                            </div>
                            <div class="ms-auto">
                                <a href="{{ route('medical-records.create', $currentPatient) }}" 
                                   class="btn btn-primary btn-lg">
                                    <i class="fas fa-stethoscope me-2"></i> Input Pemeriksaan
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Waiting List -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="60">No</th>
                                    <th>Pasien</th>
                                    <th>No. RM</th>
                                    <th>Waktu Daftar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($visits as $visit)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $visit->patient->nama }}</strong><br>
                                        <small class="text-muted">Umur: {{ $visit->patient->umur }} thn</small>
                                    </td>
                                    <td>{{ $visit->patient->no_rekam_medis }}</td>
                                    <td>{{ $visit->created_at->format('H:i') }}</td>
                                    <td>
                                        <span class="badge badge-status-{{ $visit->status }}">
                                            {{ ucfirst($visit->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('patients.show', $visit->patient) }}" 
                                               class="btn btn-sm btn-info" title="Detail Pasien">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            
                                            @if($visit->status == 'menunggu')
                                            <form action="{{ route('visits.updateStatus', $visit) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="diperiksa">
                                                <button type="submit" class="btn btn-sm btn-primary" 
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
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        title="Selesaikan">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5>Tidak ada pasien dalam antrian hari ini</h5>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .badge-status-menunggu {
        background-color: #ffc107;
        color: #000;
    }
    .badge-status-diperiksa {
        background-color: #17a2b8;
        color: #fff;
    }
    .badge-status-selesai {
        background-color: #28a745;
        color: #fff;
    }
</style>
@endsection