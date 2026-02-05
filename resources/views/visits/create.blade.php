@extends('layouts.app')

@section('title', 'Daftarkan Kunjungan Baru')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar-plus me-2"></i>Daftarkan Kunjungan Baru
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('visits.store') }}" id="visitForm">
                    @csrf
                    
                    <!-- Display validation errors -->
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <!-- Patient Selection - SIMPLIFIED -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user-injured me-2"></i>Pilih Pasien
                            </h6>
                        </div>
                        
                        <div class="col-md-8">
                            <!-- Option 1: Select from existing patients -->
                            <div class="mb-4">
                                <label for="patient_id" class="form-label">Pilih Pasien yang Sudah Terdaftar <span class="text-danger">*</span></label>
                                <select class="form-select @error('patient_id') is-invalid @enderror" 
                                        id="patient_id" name="patient_id" required>
                                    <option value="">-- Pilih Pasien --</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" 
                                                {{ old('patient_id') == $patient->id ? 'selected' : '' }}
                                                data-nama="{{ $patient->nama }}"
                                                data-no-rekam-medis="{{ $patient->no_rekam_medis }}"
                                                data-umur="{{ $patient->umur }}"
                                                data-jenis-kelamin="{{ $patient->jenis_kelamin }}"
                                                data-no-hp="{{ $patient->no_hp }}"
                                                data-alamat="{{ $patient->alamat }}">
                                            {{ $patient->nama }} - {{ $patient->no_rekam_medis }} 
                                            ({{ $patient->jenis_kelamin == 'L' ? 'L' : 'P' }}, {{ $patient->umur }} tahun)
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Pilih pasien dari daftar yang sudah terdaftar
                                </div>
                            </div>
                            
                            <!-- Patient Details Preview -->
                            <div class="card border-primary mb-3" id="patientDetailsCard" style="display: none;">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Detail Pasien Terpilih</h6>
                                </div>
                                <div class="card-body">
                                    <div id="patientDetails">
                                        <!-- Patient details will be shown here -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quick search option -->
                            <div class="mb-3">
                                <label class="form-label">Cari Pasien Cepat</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="quickSearch" 
                                           placeholder="Ketik nama atau No. RM...">
                                    <button class="btn btn-outline-secondary" type="button" id="quickSearchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="form-text">
                                    Pencarian akan memfilter dropdown di atas
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Option 2: Register new patient -->
                            <div class="card bg-light mb-3">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                                    <h6>Pasien Baru</h6>
                                    <p class="text-muted small mb-3">
                                        Jika pasien belum terdaftar, daftarkan sebagai pasien baru
                                    </p>
                                    <a href="{{ route('patients.create') }}" class="btn btn-primary mb-2" target="_blank">
                                        <i class="fas fa-plus-circle me-2"></i>Daftar Pasien Baru
                                    </a>
                                    <p class="small text-muted mt-2 mb-0">
                                        Setelah mendaftar, refresh halaman ini untuk melihat pasien baru di daftar
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Quick patient info -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Total Pasien</h6>
                                </div>
                                <div class="card-body text-center">
                                    <h2 class="text-primary">{{ $patients->count() }}</h2>
                                    <p class="text-muted small mb-0">Pasien terdaftar</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Visit Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Informasi Kunjungan
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_kunjungan') is-invalid @enderror" 
                                   id="tanggal_kunjungan" name="tanggal_kunjungan" 
                                   value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}" required>
                            @error('tanggal_kunjungan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jam_kunjungan" class="form-label">Jam Kunjungan <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('jam_kunjungan') is-invalid @enderror" 
                                   id="jam_kunjungan" name="jam_kunjungan" 
                                   value="{{ old('jam_kunjungan', date('H:i')) }}" required>
                            @error('jam_kunjungan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="doctor_id" class="form-label">Dokter <span class="text-danger">*</span></label>
                            <select class="form-select @error('doctor_id') is-invalid @enderror" 
                                    id="doctor_id" name="doctor_id" required>
                                <option value="">Pilih Dokter...</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="poli" class="form-label">Poliklinik <span class="text-danger">*</span></label>
                            <select class="form-select @error('poli') is-invalid @enderror" 
                                    id="poli" name="poli" required>
                                <option value="">Pilih Poliklinik...</option>
                                <option value="umum" {{ old('poli') == 'umum' ? 'selected' : '' }}>Umum</option>
                                <option value="gigi" {{ old('poli') == 'gigi' ? 'selected' : '' }}>Gigi</option>
                                <option value="anak" {{ old('poli') == 'anak' ? 'selected' : '' }}>Anak</option>
                                <option value="kandungan" {{ old('poli') == 'kandungan' ? 'selected' : '' }}>Kandungan</option>
                                <option value="bedah" {{ old('poli') == 'bedah' ? 'selected' : '' }}>Bedah</option>
                                <option value="mata" {{ old('poli') == 'mata' ? 'selected' : '' }}>Mata</option>
                                <option value="kulit" {{ old('poli') == 'kulit' ? 'selected' : '' }}>Kulit & Kelamin</option>
                            </select>
                            @error('poli')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="col-12 mb-3">
                            <label for="keluhan_utama" class="form-label">Keluhan Utama</label>
                            <textarea class="form-control @error('keluhan_utama') is-invalid @enderror" 
                                      id="keluhan_utama" name="keluhan_utama" rows="2" 
                                      placeholder="Keluhan utama pasien...">{{ old('keluhan_utama') }}</textarea>
                            @error('keluhan_utama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Priority -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prioritas</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="prioritas" name="prioritas" 
                                       value="1" {{ old('prioritas') ? 'checked' : '' }}>
                                <label class="form-check-label" for="prioritas">
                                    <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                    Kunjungan Prioritas (Lansia/Hamil/Disabilitas/Darurat)
                                </label>
                            </div>
                            <div class="form-text">
                                Kunjungan prioritas akan mendapat antrian lebih cepat
                            </div>
                        </div>
                        
                        <!-- Payment Type -->
                        <div class="col-md-6 mb-3">
                            <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
                            <select class="form-select" id="jenis_pembayaran" name="jenis_pembayaran">
                                <option value="umum" {{ old('jenis_pembayaran') == 'umum' ? 'selected' : '' }}>Umum</option>
                                <option value="bpjs" {{ old('jenis_pembayaran') == 'bpjs' ? 'selected' : '' }}>BPJS</option>
                                <option value="asuransi" {{ old('jenis_pembayaran') == 'asuransi' ? 'selected' : '' }}>Asuransi Lain</option>
                            </select>
                        </div>
                        
                        <!-- Notes -->
                        <div class="col-12 mb-3">
                            <label for="catatan" class="form-label">Catatan Tambahan (Opsional)</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="2" 
                                      placeholder="Catatan tambahan untuk kunjungan ini...">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-12 text-end">
                            <a href="{{ route('visits.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Kunjungan
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
        const patientSelect = document.getElementById('patient_id');
        const patientDetailsCard = document.getElementById('patientDetailsCard');
        const patientDetails = document.getElementById('patientDetails');
        const quickSearchInput = document.getElementById('quickSearch');
        const quickSearchBtn = document.getElementById('quickSearchBtn');
        
        // Show patient details when a patient is selected
        patientSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value) {
                // Show patient details
                patientDetailsCard.style.display = 'block';
                
                const patientInfo = `
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nama:</strong> ${selectedOption.getAttribute('data-nama')}</p>
                            <p class="mb-1"><strong>No. RM:</strong> ${selectedOption.getAttribute('data-no-rekam-medis')}</p>
                            <p class="mb-1"><strong>Usia:</strong> ${selectedOption.getAttribute('data-umur')} tahun</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Jenis Kelamin:</strong> ${selectedOption.getAttribute('data-jenis-kelamin') == 'L' ? 'Laki-laki' : 'Perempuan'}</p>
                            <p class="mb-1"><strong>No. HP:</strong> ${selectedOption.getAttribute('data-no-hp') || '-'}</p>
                            <p class="mb-0"><strong>Alamat:</strong> ${selectedOption.getAttribute('data-alamat') || '-'}</p>
                        </div>
                    </div>
                `;
                
                patientDetails.innerHTML = patientInfo;
            } else {
                // Hide patient details if no patient selected
                patientDetailsCard.style.display = 'none';
                patientDetails.innerHTML = '';
            }
        });
        
        // Quick search functionality - simple filter
        function filterPatients() {
            const searchTerm = quickSearchInput.value.toLowerCase().trim();
            const options = patientSelect.options;
            
            if (!searchTerm) {
                // Show all options
                for (let i = 0; i < options.length; i++) {
                    options[i].style.display = '';
                }
                return;
            }
            
            // Filter options based on search term
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                const text = option.text.toLowerCase();
                
                if (text.includes(searchTerm) || option.value === '') {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
        }
        
        // Add event listeners for quick search
        quickSearchBtn.addEventListener('click', filterPatients);
        quickSearchInput.addEventListener('keyup', filterPatients);
        
        // Trigger change event if there's already a selected value (for form validation errors)
        if (patientSelect.value) {
            patientSelect.dispatchEvent(new Event('change'));
        }
        
        // Form validation
        document.getElementById('visitForm').addEventListener('submit', function(e) {
            // Simple validation - check if patient is selected
            if (!patientSelect.value) {
                e.preventDefault();
                alert('Pilih pasien terlebih dahulu!');
                patientSelect.focus();
                return;
            }
            
            // Optional: Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });
    });
</script>
@endsection