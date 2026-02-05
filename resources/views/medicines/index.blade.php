@extends('layouts.app')

@section('title', 'Manajemen Obat')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Data Obat</h5>
                    <div>
                        <a href="{{ route('medicines.create') }}" class="btn btn-primary me-2">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Obat
                        </a>
                        <div class="btn-group">
                            <a href="{{ route('medicines.low-stock') }}" class="btn btn-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>Stok Rendah
                            </a>
                            <a href="{{ route('medicines.expired-soon') }}" class="btn btn-danger">
                                <i class="fas fa-calendar-times me-2"></i>Kedaluwarsa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="jenis" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Jenis</option>
                                    @foreach($medicineTypes as $type)
                                        <option value="{{ $type }}" {{ request('jenis') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="stock" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Stok</option>
                                    <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stok Rendah (≤10)</option>
                                    <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Cari nama atau kode obat..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Stock Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">Total Jenis Obat</h6>
                                        <h4 class="mb-0">{{ $medicines->total() }}</h4>
                                    </div>
                                    <i class="fas fa-pills fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        
                            <h4>{{ number_format($totalStock) }}</h4>
<h4>Rp {{ number_format($totalValue) }}</h4>

                        
                        <div class="card bg-success text-white">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">Total Stok</h6>
                                        <h4 class="mb-0">{{ number_format($totalStock) }}</h4>
                                    </div>
                                    <i class="fas fa-boxes fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">Nilai Stok</h6>
                                        <h4 class="mb-0">Rp {{ number_format($totalValue, 0, ',', '.') }}</h4>
                                    </div>
                                    <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        
                        <div class="card {{ $lowStockCount > 0 ? 'bg-warning' : 'bg-secondary' }} text-white">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between">
            <div>
                <h6 class="mb-0">Stok Rendah</h6>
                <h4 class="mb-0">{{ $lowStockCount }}</h4>
            </div>
            <i class="fas fa-exclamation-triangle fa-2x opacity-50"></i>
        </div>
    </div>
</div>

                    </div>
                </div>

                <!-- Medicine List -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Obat</th>
                                <th>Jenis</th>
                                <th>Stok</th>
                                <th>Harga Satuan</th>
                                <th>Kadaluwarsa</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicines as $medicine)
                            <tr>
                                <td>{{ $loop->iteration + (($medicines->currentPage() - 1) * $medicines->perPage()) }}</td>
                                <td><strong>{{ $medicine->kode_obat }}</strong></td>
                                <td>{{ $medicine->nama_obat }}</td>
                                <td>{{ $medicine->jenis_obat }}</td>
                                <td>
                                    <span class="badge 
                                        @if($medicine->stok == 0) bg-danger
                                        @elseif($medicine->stok <= 10) bg-warning
                                        @else bg-success @endif">
                                        {{ $medicine->stok }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($medicine->harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($medicine->expired_date)
                                        @if($medicine->expired_date->isPast())
                                            <span class="text-danger">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $medicine->expired_date->format('d/m/Y') }}
                                            </span>
                                        @elseif($medicine->expired_date->diffInDays(now()) <= 30)
                                            <span class="text-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $medicine->expired_date->format('d/m/Y') }}
                                            </span>
                                        @else
                                            {{ $medicine->expired_date->format('d/m/Y') }}
                                        @endif
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
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('medicines.stock-history', $medicine) }}" 
                                           class="btn btn-sm btn-info" title="Riwayat Stok">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        <a href="{{ route('medicines.edit', $medicine) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $medicine->id }}"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $medicine->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Obat</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus obat:</p>
                                                    <p><strong>{{ $medicine->nama_obat }}</strong> ({{ $medicine->kode_obat }})</p>
                                                    @if($medicine->prescriptions()->count() > 0)
                                                    <div class="alert alert-danger">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        Obat ini telah digunakan dalam {{ $medicine->prescriptions()->count() }} resep.
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('medicines.destroy', $medicine) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data obat</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $medicines->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection