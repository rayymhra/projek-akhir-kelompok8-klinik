@extends('layouts.app')

@section('title', 'Edit Data Pasien')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-edit me-2"></i>Edit Data Pasien
                    </h5>
                    <div>
                        <span class="badge bg-secondary">{{ $patient->no_rekam_medis }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('patients.update', $patient) }}" id="patientForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Patient Info Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                    <div>
                                        <strong>Perhatian:</strong> Pastikan data yang diubah sudah benar. 
                                        Perubahan akan mempengaruhi semua riwayat pasien.
                                    </div>
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
                            <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                   id="nama" name="nama" value="{{ old('nama', $patient->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="no_rekam_medis" class="form-label">No. Rekam Medis</label>
                            <input type="text" class="form-control bg-light" 
                                   id="no_rekam_medis" value="{{ $patient->no_rekam_medis }}" readonly>
                            <div class="form-text">No. RM tidak dapat diubah</div>
                        </div>
                    </div>
                    
                    <!-- Birth Information -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                                   id="tanggal_lahir" name="tanggal_lahir" 
                                   value="{{ old('tanggal_lahir', $patient->tanggal_lahir->format('Y-m-d')) }}" required>
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <span class="badge bg-info" id="ageDisplay">
                                    Usia: {{ $patient->umur }} tahun
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <div class="d-flex">
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" 
                                           id="laki" value="L" 
                                           {{ old('jenis_kelamin', $patient->jenis_kelamin) == 'L' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="laki">
                                        <i class="fas fa-mars me-1"></i>Laki-laki
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" 
                                           id="perempuan" value="P" 
                                           {{ old('jenis_kelamin', $patient->jenis_kelamin) == 'P' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perempuan">
                                        <i class="fas fa-venus me-1"></i>Perempuan
                                    </label>
                                </div>
                            </div>
                            @error('jenis_kelamin')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-address-book me-2"></i>Informasi Kontak
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" class="form-control @error('no_hp') is-invalid @enderror" 
                                       id="no_hp" name="no_hp" 
                                       value="{{ old('no_hp', $patient->no_hp) }}" required 
                                       placeholder="81234567890">
                            </div>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="no_hp_keluarga" class="form-label">No. HP Keluarga (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="tel" class="form-control @error('no_hp_keluarga') is-invalid @enderror" 
                                       id="no_hp_keluarga" name="no_hp_keluarga" 
                                       value="{{ old('no_hp_keluarga', $patient->no_hp_keluarga ?? '') }}" 
                                       placeholder="81234567890">
                            </div>
                            @error('no_hp_keluarga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Address Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-home me-2"></i>Informasi Alamat
                            </h6>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" name="alamat" rows="3" required>{{ old('alamat', $patient->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="rt" class="form-label">RT (Opsional)</label>
                            <input type="text" class="form-control @error('rt') is-invalid @enderror" 
                                   id="rt" name="rt" value="{{ old('rt', $patient->rt ?? '') }}" 
                                   placeholder="001" maxlength="3">
                            @error('rt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="rw" class="form-label">RW (Opsional)</label>
                            <input type="text" class="form-control @error('rw') is-invalid @enderror" 
                                   id="rw" name="rw" value="{{ old('rw', $patient->rw ?? '') }}" 
                                   placeholder="002" maxlength="3">
                            @error('rw')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="kode_pos" class="form-label">Kode Pos (Opsional)</label>
                            <input type="text" class="form-control @error('kode_pos') is-invalid @enderror" 
                                   id="kode_pos" name="kode_pos" 
                                   value="{{ old('kode_pos', $patient->kode_pos ?? '') }}" 
                                   placeholder="12345" maxlength="5">
                            @error('kode_pos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-plus-circle me-2"></i>Informasi Tambahan
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="golongan_darah" class="form-label">Golongan Darah (Opsional)</label>
                            <select class="form-select @error('golongan_darah') is-invalid @enderror" 
                                    id="golongan_darah" name="golongan_darah">
                                <option value="">Pilih Golongan Darah...</option>
                                <option value="A" {{ old('golongan_darah', $patient->golongan_darah ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('golongan_darah', $patient->golongan_darah ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="AB" {{ old('golongan_darah', $patient->golongan_darah ?? '') == 'AB' ? 'selected' : '' }}>AB</option>
                                <option value="O" {{ old('golongan_darah', $patient->golongan_darah ?? '') == 'O' ? 'selected' : '' }}>O</option>
                            </select>
                            @error('golongan_darah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="alergi" class="form-label">Alergi (Opsional)</label>
                            <input type="text" class="form-control @error('alergi') is-invalid @enderror" 
                                   id="alergi" name="alergi" 
                                   value="{{ old('alergi', $patient->alergi ?? '') }}" 
                                   placeholder="Contoh: Penisilin, Debu, Makanan Laut">
                            @error('alergi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="catatan" class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" name="catatan" rows="2">{{ old('catatan', $patient->catatan ?? '') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Patient Statistics -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Statistik Pasien
                            </h6>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Total Kunjungan</h6>
                                    <h5 class="mb-0">{{ $patient->visits()->count() }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Terdaftar Sejak</h6>
                                    <h5 class="mb-0">{{ $patient->created_at->format('d/m/Y') }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Kunjungan Terakhir</h6>
                                    @php
                                        $lastVisit = $patient->visits()->latest()->first();
                                    @endphp
                                    <h5 class="mb-0">
                                        @if($lastVisit)
                                            {{ $lastVisit->created_at->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Status</h6>
                                    @if($patient->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-danger">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('patients.show', $patient) }}" class="btn btn-secondary w-100">
                                <i class="fas fa-times me-2"></i>Batal
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('patientForm');
        const submitBtn = document.getElementById('submitBtn');
        const tanggalLahirInput = document.getElementById('tanggal_lahir');
        const ageDisplay = document.getElementById('ageDisplay');
        const noHpInput = document.getElementById('no_hp');
        const noHpKeluargaInput = document.getElementById('no_hp_keluarga');
        
        // Calculate age from birth date
        function calculateAge() {
            const birthDate = new Date(tanggalLahirInput.value);
            const today = new Date();
            
            if (isNaN(birthDate.getTime())) return;
            
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            ageDisplay.textContent = `Usia: ${age} tahun`;
        }
        
        // Format phone numbers
        function formatPhoneNumber(input) {
            let value = input.value.replace(/\D/g, '');
            
            // Remove leading zero if exists
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            
            // Limit to 12 digits max
            value = value.substring(0, 12);
            
            input.value = value;
        }
        
        // Form validation before submit
        form.addEventListener('submit', function(e) {
            // Validate phone number
            const phoneRegex = /^[0-9]{9,12}$/;
            
            if (!phoneRegex.test(noHpInput.value.replace(/\D/g, ''))) {
                e.preventDefault();
                showAlert('No. HP tidak valid. Minimal 9 digit, maksimal 12 digit.', 'danger');
                noHpInput.focus();
                return;
            }
            
            if (noHpKeluargaInput.value && !phoneRegex.test(noHpKeluargaInput.value.replace(/\D/g, ''))) {
                e.preventDefault();
                showAlert('No. HP Keluarga tidak valid. Minimal 9 digit, maksimal 12 digit.', 'danger');
                noHpKeluargaInput.focus();
                return;
            }
            
            // Validate birth date
            const birthDate = new Date(tanggalLahirInput.value);
            const today = new Date();
            
            if (birthDate > today) {
                e.preventDefault();
                showAlert('Tanggal lahir tidak boleh lebih dari hari ini.', 'danger');
                tanggalLahirInput.focus();
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });
        
        // Reset form
        function resetForm() {
            if (confirm('Reset semua perubahan?')) {
                form.reset();
                calculateAge();
            }
        }
        
        // Show alert message
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
        
        // Event listeners
        tanggalLahirInput.addEventListener('change', calculateAge);
        noHpInput.addEventListener('input', () => formatPhoneNumber(noHpInput));
        noHpKeluargaInput.addEventListener('input', () => formatPhoneNumber(noHpKeluargaInput));
        
        // Initialize
        calculateAge();
        formatPhoneNumber(noHpInput);
        formatPhoneNumber(noHpKeluargaInput);
        
        // RT/RW input validation
        document.getElementById('rt').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 3);
        });
        
        document.getElementById('rw').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 3);
        });
        
        document.getElementById('kode_pos').addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').slice(0, 5);
        });
    });
</script>
@endsection