@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Daftar Pengguna</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="row g-2">
                            <div class="col-md-5">
                                <select name="role" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="dokter" {{ request('role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                                    <option value="kasir" {{ request('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Cari nama atau email..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Tambah Pengguna
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + (($users->currentPage() - 1) * $users->perPage()) }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @switch($user->role)
                                        @case('admin')
                                            <span class="badge bg-danger">Admin</span>
                                            @break
                                        @case('petugas')
                                            <span class="badge bg-primary">Petugas</span>
                                            @break
                                        @case('dokter')
                                            <span class="badge bg-success">Dokter</span>
                                            @break
                                        @case('kasir')
                                            <span class="badge bg-warning">Kasir</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $user->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Pengguna</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus pengguna <strong>{{ $user->name }}</strong>?</p>
                                                    <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST">
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
                                <td colspan="6" class="text-center">Tidak ada data pengguna</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection