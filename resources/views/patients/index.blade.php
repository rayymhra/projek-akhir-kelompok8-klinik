@extends('layouts.app')

@section('title', 'Data Pasien')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Daftar Pasien</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="row g-2">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Cari nama, no. rekam medis, atau no. HP..." 
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('patients.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Tambah Pasien
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Rekam Medis</th>
                                <th>Nama</th>
                                <th>Umur</th>
                                <th>Jenis Kelamin</th>
                                <th>No. HP</th>
                                <th>Total Kunjungan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                            <tr>
                                <td>{{ $loop->iteration + (($patients->currentPage() - 1) * $patients->perPage()) }}</td>
                                <td><strong>{{ $patient->no_rekam_medis }}</strong></td>
                                <td>{{ $patient->nama }}</td>
                                <td>{{ $patient->umur }} tahun</td>
                                <td>{{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ $patient->no_hp }}</td>
                                <td>{{ $patient->visits_count ?? $patient->visits()->count() }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-info" 
                                           title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $patient->id }}"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $patient->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Pasien</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus data pasien:</p>
                                                    <p><strong>{{ $patient->nama }}</strong> ({{ $patient->no_rekam_medis }})</p>
                                                    <p class="text-danger">Tindakan ini juga akan menghapus semua data kunjungan terkait.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST">
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
                                <td colspan="8" class="text-center">Tidak ada data pasien</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection