@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.store') }}" id="userForm">
                    @csrf
                    
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
                                   id="name" name="name" value="{{ old('name') }}" required 
                                   placeholder="Masukkan nama lengkap">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required 
                                   placeholder="contoh@email.com">
                            <div class="form-text">Email akan digunakan untuk login</div>
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
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Pendaftaran</option>
                                <option value="dokter" {{ old('role') == 'dokter' ? 'selected' : '' }}>Dokter</option>
                                <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deskripsi Role</label>
                            <div id="roleDescription" class="alert alert-info py-2">
                                <small>Pilih role untuk melihat deskripsi</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-key me-2"></i>Password
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required 
                                       placeholder="Minimal 8 karakter">
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
                            <small class="text-muted" id="passwordStrengthText">Kekuatan password: sangat lemah</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required 
                                       placeholder="Ketik ulang password">
                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="mt-2" id="passwordMatch"></div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                            </h6>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="send_welcome_email" name="send_welcome_email">
                                <label class="form-check-label" for="send_welcome_email">
                                    Kirim email selamat datang dengan informasi login
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Perhatian:</strong> Pastikan data yang dimasukkan sudah benar. 
                                Pengguna akan dapat mengakses sistem sesuai dengan role yang diberikan.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Simpan Pengguna
                            </button>
                        </div>
                    </div>
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

@section('styles')
<style>
    .password-strength-weak {
        background-color: #dc3545;
    }
    
    .password-strength-fair {
        background-color: #ffc107;
    }
    
    .password-strength-good {
        background-color: #28a745;
    }
    
    .password-strength-strong {
        background-color: #20c997;
    }
    
    .role-card {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .role-card:hover {
        border-color: #4e73df;
        background-color: #f8f9fa;
    }
    
    .role-card.selected {
        border-color: #4e73df;
        background-color: #e3f2fd;
    }
</style>
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
                roleDescription.className = 'alert alert-info py-2';
            } else {
                roleDescription.innerHTML = '<small>Pilih role untuk melihat deskripsi</small>';
                roleDescription.className = 'alert alert-info py-2';
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
        
        // Password strength checker
        passwordInput.addEventListener('input', function() {
            checkPasswordStrength(this.value);
            checkPasswordMatch();
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
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });
        
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
        checkPasswordStrength(passwordInput.value);
    });
</script>
@endsection