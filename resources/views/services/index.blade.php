@extends('layouts.app')

@section('title', 'Manajemen Layanan')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-hand-holding-medical text-primary"></i> Daftar Layanan</h2>
            <p class="text-muted">Kelola layanan yang tersedia di klinik</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('services.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Layanan Baru
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">Total Layanan</h5>
                            <h2 class="mb-0">{{ $services->total() }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services List -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if($services->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Kode</th>
                                <th>Nama Layanan</th>
                                <th>Tarif</th>
                                <th>Deskripsi</th>
                                <th>Dibuat</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $index => $service)
                            <tr>
                                <td>{{ $services->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $service->kode_layanan }}</span>
                                </td>
                                <td>
                                    <strong>{{ $service->nama_layanan }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        Rp {{ number_format($service->tarif, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ Str::limit($service->deskripsi, 50) ?: '-' }}
                                    </small>
                                </td>
                                <td>
                                    <small>{{ $service->created_at->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('services.edit', $service) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $service->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $service->id }}" tabindex="-1" 
                                 aria-labelledby="deleteModalLabel{{ $service->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $service->id }}">
                                                Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus layanan ini?</p>
                                            <div class="alert alert-warning">
                                                <strong>{{ $service->nama_layanan }}</strong><br>
                                                Kode: {{ $service->kode_layanan }}<br>
                                                Tarif: Rp {{ number_format($service->tarif, 0, ',', '.') }}
                                            </div>
                                            <p class="text-danger">
                                                <small>
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    Data yang dihapus tidak dapat dikembalikan.
                                                </small>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('services.destroy', $service) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Menampilkan {{ $services->firstItem() }} - {{ $services->lastItem() }} dari {{ $services->total() }} layanan
                    </div>
                    <div>
                        {{ $services->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-hand-holding-medical fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Belum ada layanan</h4>
                    <p class="text-muted">Mulai dengan menambahkan layanan baru.</p>
                    <a href="{{ route('services.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Tambah Layanan Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Info -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6><i class="fas fa-lightbulb text-warning me-2"></i> Tips:</h6>
                    <ul>
                        <li>Kode layanan akan dibuat otomatis jika dikosongkan</li>
                        <li>Layanan yang sudah digunakan dalam transaksi tidak dapat dihapus</li>
                        <li>Pastikan tarif sudah sesuai dengan standar klinik</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6><i class="fas fa-clipboard-check text-success me-2"></i> Contoh Layanan:</h6>
                    <ul>
                        <li>Konsultasi Dokter Umum</li>
                        <li>Pemeriksaan Darah</li>
                        <li>Perawatan Luka</li>
                        <li>Imunisasi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table th {
        font-weight: 600;
        border-top: none;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .badge {
        font-size: 0.85em;
        padding: 5px 10px;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection