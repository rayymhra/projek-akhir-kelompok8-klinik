@extends('layouts.app')

@section('title', 'Edit Data Pengguna')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-edit me-2"></i>Edit Data Pengguna
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update', $user) }}" id="userForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- User Info Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <div class="avatar-placeholder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; font-size: 24px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 
                                                            ($user->role == 'petugas' ? 'primary' : 
                                                            ($user->role == 'dokter' ? 'success' : 'warning')) }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Personal Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-id-card me-2"></i>Informasi Pribadi
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Role Selection -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user-tag me-2"></i>Hak Akses
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    name="role" id="role" required onchange="showRoleDescription()">
                                <option value="">Pilih Role...</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas Pendaftaran</option>
                                <option value="dokter" {{ old('role', $user->role) == 'dokter' ? 'selected' : '' }}>Dokter</option>
                                <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deskripsi Role</label>
                            <div id="roleDescription" class="alert alert-info py-2">
                                @switch($user->role)
                                    @case('admin')
                                        <strong>Admin</strong><br>
                                        <small class="text-muted">
                                            Hak akses penuh untuk semua modul sistem. Dapat mengelola pengguna, data master, dan laporan.
                                        </small>
                                        @break
                                    @case('petugas')
                                        <strong>Petugas Pendaftaran</strong><br>
                                        <small class="text-muted">
                                            Dapat mendaftarkan pasien baru, mengelola data pasien, dan membuat kunjungan.
                                        </small>
                                        @break
                                    @case('dokter')
                                        <strong>Dokter</strong><br>
                                        <small class="text-muted">
                                            Dapat menginput hasil pemeriksaan, diagnosa, tindakan, dan resep obat. Dapat melihat riwayat pasien.
                                        </small>
                                        @break
                                    @case('kasir')
                                        <strong>Kasir</strong><br>
                                        <small class="text-muted">
                                            Dapat mengelola transaksi dan pembayaran pasien. Dapat mencetak struk pembayaran.
                                        </small>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Change (Optional) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-key me-2"></i>Ubah Password (Opsional)
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" 
                                       placeholder="Kosongkan jika tidak ingin mengubah">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="progress mt-2" style="height: 5px;">
                                <div class="progress-bar" id="passwordStrength" role="progressbar" 
                                     style="width: 0%;"></div>
                            </div>
                            <small class="text-muted" id="passwordStrengthText">Kosongkan jika tidak ingin mengubah password</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" 
                                       placeholder="Konfirmasi password baru">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2" id="passwordMatch"></div>
                        </div>
                    </div>
                    
                    <!-- User Statistics -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Statistik Pengguna
                            </h6>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Bergabung</h6>
                                    <h5 class="mb-0">{{ $user->created_at->format('d/m/Y') }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Terakhir Login</h6>
                                    <h5 class="mb-0">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Belum pernah' }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Status</h6>
                                    @if($user->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Total Login</h6>
                                    <h5 class="mb-0">{{ $user->login_count ?? 0 }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-warning w-100" onclick="resetForm()">
                                <i class="fas fa-redo me-2"></i>Reset
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
                
                <!-- Danger Zone -->
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Zona Bahaya
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="text-danger">Nonaktifkan Akun</h6>
                                        <p class="text-muted mb-0">
                                            Menonaktifkan akun akan mencegah pengguna untuk login. Data tidak akan dihapus.
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button class="btn btn-outline-danger" data-bs-toggle="modal" 
                                                data-bs-target="#deactivateModal" {{ $user->role == 'admin' && $user->is_admin ? 'disabled' : '' }}>
                                            <i class="fas fa-user-slash me-2"></i>Nonaktifkan
                                        </button>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="text-danger">Hapus Akun</h6>
                                        <p class="text-muted mb-0">
                                            Menghapus akun secara permanen. Tindakan ini tidak dapat dibatalkan.
                                        </p>
                                        @if($user->role == 'admin')
                                        <small class="text-danger">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            Tidak dapat menghapus akun admin
                                        </small>
                                        @endif
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button class="btn btn-danger" data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" {{ $user->role == 'admin' ? 'disabled' : '' }}>
                                            <i class="fas fa-trash me-2"></i>Hapus Akun
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Deactivate Modal -->
<div class="modal fade" id="deactivateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-user-slash me-2"></i>Nonaktifkan Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menonaktifkan akun <strong>{{ $user->name }}</strong>?</p>
                <p class="text-muted">
                    Pengguna tidak akan dapat login sampai akun diaktifkan kembali.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('users.deactivate', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">Nonaktifkan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-trash me-2"></i>Hapus Akun
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus akun <strong>{{ $user->name }}</strong> secara permanen?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>PERHATIAN:</strong> Tindakan ini tidak dapat dibatalkan. Semua data terkait akun ini akan dihapus.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Role Descriptions -->
<template id="adminDesc">
    <strong>Admin</strong><br>
    <small class="text-muted">
        Hak akses penuh untuk semua modul sistem. Dapat mengelola pengguna, data master, dan laporan.
    </small>
</template>

<template id="petugasDesc">
    <strong>Petugas Pendaftaran</strong><br>
    <small class="text-muted">
        Dapat mendaftarkan pasien baru, mengelola data pasien, dan membuat kunjungan.
    </small>
</template>

<template id="dokterDesc">
    <strong>Dokter</strong><br>
    <small class="text-muted">
        Dapat menginput hasil pemeriksaan, diagnosa, tindakan, dan resep obat. Dapat melihat riwayat pasien.
    </small>
</template>

<template id="kasirDesc">
    <strong>Kasir</strong><br>
    <small class="text-muted">
        Dapat mengelola transaksi dan pembayaran pasien. Dapat mencetak struk pembayaran.
    </small>
</template>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const roleDescription = document.getElementById('roleDescription');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const passwordStrengthBar = document.getElementById('passwordStrength');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const passwordMatchDiv = document.getElementById('passwordMatch');
        const form = document.getElementById('userForm');
        const submitBtn = document.getElementById('submitBtn');
        
        // Role descriptions
        const roleDescriptions = {
            'admin': document.getElementById('adminDesc').innerHTML,
            'petugas': document.getElementById('petugasDesc').innerHTML,
            'dokter': document.getElementById('dokterDesc').innerHTML,
            'kasir': document.getElementById('kasirDesc').innerHTML
        };
        
        // Show role description on change
        function showRoleDescription() {
            const role = roleSelect.value;
            if (role && roleDescriptions[role]) {
                roleDescription.innerHTML = roleDescriptions[role];
            }
        }
        
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
        
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
        
        // Password strength checker (only if password is being changed)
        passwordInput.addEventListener('input', function() {
            if (this.value) {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            } else {
                passwordStrengthBar.style.width = '0%';
                passwordStrengthText.textContent = 'Kosongkan jika tidak ingin mengubah password';
                passwordStrengthText.style.color = '';
            }
        });
        
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordStrength(password) {
            let strength = 0;
            let text = '';
            let color = '';
            let width = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Character variety checks
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Determine strength level
            switch(strength) {
                case 0:
                case 1:
                case 2:
                    text = 'Sangat lemah';
                    color = '#dc3545';
                    width = 25;
                    break;
                case 3:
                case 4:
                    text = 'Lemah';
                    color = '#ffc107';
                    width = 50;
                    break;
                case 5:
                    text = 'Baik';
                    color = '#28a745';
                    width = 75;
                    break;
                case 6:
                    text = 'Sangat kuat';
                    color = '#20c997';
                    width = 100;
                    break;
            }
            
            passwordStrengthBar.style.width = width + '%';
            passwordStrengthBar.style.backgroundColor = color;
            passwordStrengthText.textContent = 'Kekuatan password: ' + text;
            passwordStrengthText.style.color = color;
        }
        
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (!password && !confirmPassword) {
                passwordMatchDiv.innerHTML = '';
                return;
            }
            
            if (confirmPassword === '') {
                passwordMatchDiv.innerHTML = '';
                return;
            }
            
            if (password === confirmPassword) {
                passwordMatchDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Password cocok</span>';
            } else {
                passwordMatchDiv.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Password tidak cocok</span>';
            }
        }
        
        // Form validation before submit
        form.addEventListener('submit', function(e) {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            if (password || confirmPassword) {
                if (password !== confirmPassword) {
                    e.preventDefault();
                    showAlert('Password tidak cocok. Silakan periksa kembali.', 'danger');
                    return;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    showAlert('Password minimal 8 karakter.', 'danger');
                    return;
                }
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });
        
        function resetForm() {
            if (confirm('Reset semua perubahan?')) {
                form.reset();
                showRoleDescription();
                passwordStrengthBar.style.width = '0%';
                passwordStrengthText.textContent = 'Kosongkan jika tidak ingin mengubah password';
                passwordStrengthText.style.color = '';
                passwordMatchDiv.innerHTML = '';
            }
        }
        
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            form.prepend(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }
        
        // Initialize
        showRoleDescription();
    });
</script>
@endsection