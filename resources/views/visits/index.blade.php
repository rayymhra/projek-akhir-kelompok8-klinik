@extends('layouts.app')

@section('title', 'Manajemen Kunjungan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Kunjungan</h5>
                    <a href="{{ route('visits.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Kunjungan Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diperiksa" {{ request('status') == 'diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="tanggal" 
                                       value="{{ request('tanggal') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-3">
                                <select name="doctor_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Dokter</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Cari nama pasien..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">Total</h6>
                                        <h4 class="mb-0">{{ $visits->count() }}</h4>
                                    </div>
                                    <i class="fas fa-users fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit List -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pasien</th>
                                <th>No. RM</th>
                                <th>Dokter</th>
                                <th>Tanggal</th>
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
                                <td>{{ $visit->doctor->name }}</td>
                                <td>{{ $visit->tanggal_kunjungan->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-status-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                                <!-- Replace the entire td cell for actions with this: -->
<td>
    <div class="btn-group" role="group">
        <a href="{{ route('patients.show', $visit->patient) }}" 
           class="btn btn-sm btn-info" title="Detail Pasien">
            <i class="fas fa-user"></i>
        </a>
        
        <!-- Quick Status Buttons (visible to admin/petugas) -->
        @if(in_array(auth()->user()->role, ['admin', 'petugas', 'dokter']))
            <div class="btn-group" role="group">
                <!-- Menunggu Button -->
                @if($visit->status != 'menunggu')
                <form action="{{ route('visits.updateStatus', $visit) }}" 
                      method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="menunggu">
                    <button type="submit" class="btn btn-sm btn-warning" 
                            title="Set ke Menunggu"
                            @if($visit->status == 'menunggu') disabled @endif>
                        <i class="fas fa-clock"></i>
                    </button>
                </form>
                @endif
                
                <!-- Diperiksa Button -->
                @if($visit->status != 'diperiksa')
                <form action="{{ route('visits.updateStatus', $visit) }}" 
                      method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="diperiksa">
                    <button type="submit" class="btn btn-sm btn-info" 
                            title="Set ke Diperiksa"
                            @if($visit->status == 'diperiksa') disabled @endif>
                        <i class="fas fa-user-md"></i>
                    </button>
                </form>
                @endif
                
                <!-- Selesai Button -->
                @if($visit->status != 'selesai')
                <form action="{{ route('visits.updateStatus', $visit) }}" 
                      method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="selesai">
                    <button type="submit" class="btn btn-sm btn-success" 
                            title="Set ke Selesai"
                            @if($visit->status == 'selesai') disabled @endif>
                        <i class="fas fa-check-circle"></i>
                    </button>
                </form>
                @endif
            </div>
        @endif
        
        <!-- Doctor Actions -->
        @if(auth()->user()->role == 'dokter' && $visit->status == 'menunggu')
        <a href="{{ route('medical-records.create', $visit) }}" 
           class="btn btn-sm btn-primary" title="Mulai Pemeriksaan">
            <i class="fas fa-stethoscope"></i>
        </a>
        @endif
        
        <!-- Transaction Button -->
        @if($visit->status == 'selesai' && !$visit->transaction)
        <a href="{{ route('transactions.create', ['visit' => $visit->id]) }}" 
           class="btn btn-sm btn-secondary" title="Buat Transaksi">
            <i class="fas fa-cash-register"></i>
        </a>
        @endif
        
        <!-- Optional: Dropdown for mobile view -->
        <div class="dropdown d-inline">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button" 
                    data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <h6 class="dropdown-header">Ubah Status</h6>
                </li>
                <li>
                    <form action="{{ route('visits.updateStatus', $visit) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="menunggu">
                        <button type="submit" class="dropdown-item" 
                                @if($visit->status == 'menunggu') disabled @endif>
                            <i class="fas fa-clock text-warning me-2"></i>Menunggu
                        </button>
                    </form>
                </li>
                <li>
                    <form action="{{ route('visits.updateStatus', $visit) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="diperiksa">
                        <button type="submit" class="dropdown-item"
                                @if($visit->status == 'diperiksa') disabled @endif>
                            <i class="fas fa-user-md text-info me-2"></i>Diperiksa
                        </button>
                    </form>
                </li>
                <li>
                    <form action="{{ route('visits.updateStatus', $visit) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="dropdown-item"
                                @if($visit->status == 'selesai') disabled @endif>
                            <i class="fas fa-check-circle text-success me-2"></i>Selesai
                        </button>
                    </form>
                </li>
                <li><hr class="dropdown-divider"></li>
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
            </ul>
        </div>
    </div>
</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data kunjungan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $visits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Modal -->
<div class="modal fade" id="quickActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Cepat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('visits.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-2"></i>Kunjungan Baru
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('patients.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-user-plus me-2"></i>Pasien Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-refresh for waiting list every 30 seconds
    @if(request('status') == 'menunggu')
    setInterval(function() {
        location.reload();
    }, 30000);
    @endif
</script>
@endsection