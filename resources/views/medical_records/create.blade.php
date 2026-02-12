@extends('layouts.app')

@section('title', 'Input Rekam Medis')

@section('content')
<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="card shadow">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-medical me-2"></i>Input Rekam Medis
                </h5>
            </div>
            <div class="card-body">
                <!-- Patient Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user-injured me-2"></i>Informasi Pasien</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nama Pasien:</strong> {{ $visit->patient->nama }}</p>
                                        <p class="mb-1"><strong>No. Rekam Medis:</strong> {{ $visit->patient->no_rekam_medis }}</p>
                                        <p class="mb-1"><strong>Usia:</strong> {{ $visit->patient->umur }} tahun</p>
                                        <p class="mb-1"><strong>Jenis Kelamin:</strong> {{ $visit->patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Alamat:</strong> {{ $visit->patient->alamat }}</p>
                                        <p class="mb-1"><strong>No. HP:</strong> {{ $visit->patient->no_hp }}</p>
                                        <p class="mb-1"><strong>Golongan Darah:</strong> {{ $visit->patient->golongan_darah ?? '-' }}</p>
                                        <p class="mb-1"><strong>Alergi:</strong> {{ $visit->patient->alergi ?? 'Tidak ada' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visit Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Informasi Kunjungan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Tanggal Kunjungan:</strong> {{ $visit->tanggal_kunjungan->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Dokter:</strong> {{ $visit->doctor->name }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Status:</strong> 
                                            <span class="badge badge-status-{{ $visit->status }}">
                                                {{ ucfirst($visit->status) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Record Form -->
                <form method="POST" action="{{ route('medical-records.store') }}" id="medicalRecordForm">
                    @csrf
                    <input type="hidden" name="visit_id" value="{{ $visit->id }}">
                    
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Keluhan Pasien -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-comment-medical me-2"></i>Keluhan Pasien
                            </h6>
                            <div class="mb-3">
                                <label for="keluhan" class="form-label">Keluhan Utama <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('keluhan') is-invalid @enderror" 
                                          id="keluhan" name="keluhan" rows="3" 
                                          placeholder="Jelaskan keluhan utama pasien..." 
                                          required>{{ old('keluhan') }}</textarea>
                                @error('keluhan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pemeriksaan Fisik -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-stethoscope me-2"></i>Pemeriksaan Fisik
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tekanan_darah" class="form-label">Tekanan Darah</label>
                                    <input type="text" class="form-control" id="tekanan_darah" name="tekanan_darah" 
                                           value="{{ old('tekanan_darah') }}" placeholder="Contoh: 120/80 mmHg">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nadi" class="form-label">Nadi</label>
                                    <input type="text" class="form-control" id="nadi" name="nadi" 
                                           value="{{ old('nadi') }}" placeholder="Contoh: 72x/menit">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="suhu" class="form-label">Suhu Tubuh</label>
                                    <input type="text" class="form-control" id="suhu" name="suhu" 
                                           value="{{ old('suhu') }}" placeholder="Contoh: 36.5°C">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pernafasan" class="form-label">Pernafasan</label>
                                    <input type="text" class="form-control" id="pernafasan" name="pernafasan" 
                                           value="{{ old('pernafasan') }}" placeholder="Contoh: 20x/menit">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="pemeriksaan_fisik" class="form-label">Hasil Pemeriksaan Fisik Lainnya</label>
                                    <textarea class="form-control" id="pemeriksaan_fisik" name="pemeriksaan_fisik" 
                                              rows="3" placeholder="Hasil pemeriksaan fisik lainnya...">{{ old('pemeriksaan_fisik') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Diagnosa -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-diagnoses me-2"></i>Diagnosa
                            </h6>
                            <div class="mb-3">
                                <label for="diagnosa" class="form-label">Diagnosa <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('diagnosa') is-invalid @enderror" 
                                          id="diagnosa" name="diagnosa" rows="3" 
                                          placeholder="Tuliskan diagnosa utama..." 
                                          required>{{ old('diagnosa') }}</textarea>
                                @error('diagnosa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Gunakan kode ICD-10 jika memungkinkan
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tindakan & Pengobatan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-procedures me-2"></i>Tindakan & Pengobatan
                            </h6>
                            <div class="mb-3">
                                <label for="tindakan" class="form-label">Tindakan yang Dilakukan</label>
                                <textarea class="form-control" id="tindakan" name="tindakan" 
                                          rows="3" placeholder="Jelaskan tindakan yang dilakukan...">{{ old('tindakan') }}</textarea>
                            </div>

                            <!-- Prescription Section -->
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning">
                                    <h6 class="mb-0 text-white">
                                        <i class="fas fa-prescription me-2"></i>Resep Obat
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div id="prescriptionContainer">
                                        <!-- Prescription items will be added here -->
                                        <div class="prescription-item mb-3 p-3 border rounded">
                                            <div class="row">
                                                <div class="col-md-5 mb-2">
                                                    <label class="form-label">Obat</label>
                                                    <select class="form-select medicine-select" name="medicines[0][medicine_id]" required>
                                                        <option value="">Pilih Obat...</option>
                                                        @foreach($medicines as $medicine)
                                                            <option value="{{ $medicine->id }}" 
                                                                    data-stok="{{ $medicine->stok }}"
                                                                    data-satuan="{{ $medicine->satuan }}">
                                                                {{ $medicine->nama_obat }} ({{ $medicine->kode_obat }}) - Stok: {{ $medicine->stok }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2 mb-2">
                                                    <label class="form-label">Jumlah</label>
                                                    <input type="number" class="form-control quantity-input" 
                                                           name="medicines[0][jumlah]" min="1" value="1" required>
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <label class="form-label">Aturan Pakai</label>
                                                    <input type="text" class="form-control" 
                                                           name="medicines[0][aturan_pakai]" 
                                                           placeholder="Contoh: 3x1 sehari setelah makan" required>
                                                </div>
                                                <div class="col-md-1 mb-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-prescription" 
                                                            onclick="removePrescriptionItem(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="addPrescriptionItem()">
                                        <i class="fas fa-plus me-1"></i> Tambah Obat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-notes-medical me-2"></i>Catatan Tambahan
                            </h6>
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea class="form-control" id="catatan" name="catatan" 
                                          rows="3" placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('visits.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <div class="btn-group">
                                    {{-- <button type="button" class="btn btn-warning" onclick="saveDraft()">
                                        <i class="fas fa-save me-2"></i>Simpan Draft
                                    </button> --}}
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check-circle me-2"></i>Simpan & Selesaikan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .prescription-item {
        background-color: #f8f9fa;
    }
    
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
    
    .card.border-primary, .card.border-info, .card.border-warning {
        border-width: 2px !important;
    }
</style>
@endsection

@section('scripts')
<script>
    let prescriptionCounter = 1;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Validate quantity based on stock
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                validateQuantity(this);
            });
        });
        
        // Validate medicine selection
        document.querySelectorAll('.medicine-select').forEach(select => {
            select.addEventListener('change', function() {
                validateMedicineStock(this);
            });
        });
    });
    
    function addPrescriptionItem() {
        const container = document.getElementById('prescriptionContainer');
        const newItem = document.createElement('div');
        newItem.className = 'prescription-item mb-3 p-3 border rounded';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-5 mb-2">
                    <label class="form-label">Obat</label>
                    <select class="form-select medicine-select" name="medicines[${prescriptionCounter}][medicine_id]" required>
                        <option value="">Pilih Obat...</option>
                        @foreach($medicines as $medicine)
                            <option value="{{ $medicine->id }}" 
                                    data-stok="{{ $medicine->stok }}"
                                    data-satuan="{{ $medicine->satuan }}">
                                {{ $medicine->nama_obat }} ({{ $medicine->kode_obat }}) - Stok: {{ $medicine->stok }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control quantity-input" 
                           name="medicines[${prescriptionCounter}][jumlah]" min="1" value="1" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">Aturan Pakai</label>
                    <input type="text" class="form-control" 
                           name="medicines[${prescriptionCounter}][aturan_pakai]" 
                           placeholder="Contoh: 3x1 sehari setelah makan" required>
                </div>
                <div class="col-md-1 mb-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-prescription" 
                            onclick="removePrescriptionItem(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        
        // Add event listeners to new elements
        newItem.querySelector('.medicine-select').addEventListener('change', function() {
            validateMedicineStock(this);
        });
        newItem.querySelector('.quantity-input').addEventListener('change', function() {
            validateQuantity(this);
        });
        
        prescriptionCounter++;
    }
    
    function removePrescriptionItem(button) {
        const item = button.closest('.prescription-item');
        // Only remove if there's more than one item
        if (document.querySelectorAll('.prescription-item').length > 1) {
            item.remove();
        } else {
            alert('Minimal satu item obat harus diisi.');
        }
    }
    
    function validateMedicineStock(select) {
        const selectedOption = select.options[select.selectedIndex];
        const quantityInput = select.closest('.row').querySelector('.quantity-input');
        
        if (selectedOption.value) {
            const stock = parseInt(selectedOption.getAttribute('data-stok'));
            const currentQuantity = parseInt(quantityInput.value) || 1;
            
            if (currentQuantity > stock) {
                alert(`Stok obat tidak mencukupi! Stok tersedia: ${stock}`);
                quantityInput.value = Math.min(currentQuantity, stock);
            }
            
            quantityInput.max = stock;
        }
    }
    
    function validateQuantity(input) {
        const select = input.closest('.row').querySelector('.medicine-select');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption.value) {
            const stock = parseInt(selectedOption.getAttribute('data-stok'));
            const quantity = parseInt(input.value);
            
            if (quantity > stock) {
                alert(`Stok obat tidak mencukupi! Stok tersedia: ${stock}`);
                input.value = stock;
            }
            
            if (quantity < 1) {
                input.value = 1;
            }
        }
    }
    
    function saveDraft() {
        // For now, just submit the form but mark as draft
        const form = document.getElementById('medicalRecordForm');
        const draftInput = document.createElement('input');
        draftInput.type = 'hidden';
        draftInput.name = 'draft';
        draftInput.value = '1';
        form.appendChild(draftInput);
        form.submit();
    }
    
    // Form validation before submit
    document.getElementById('medicalRecordForm').addEventListener('submit', function(e) {
        const keluhan = document.getElementById('keluhan').value.trim();
        const diagnosa = document.getElementById('diagnosa').value.trim();
        
        if (!keluhan || !diagnosa) {
            e.preventDefault();
            alert('Harap isi keluhan dan diagnosa sebelum menyimpan.');
            return;
        }
        
        // Validate prescriptions
        let validPrescriptions = true;
        document.querySelectorAll('.medicine-select').forEach(select => {
            if (select.value && !select.closest('.row').querySelector('input[name*="aturan_pakai"]').value.trim()) {
                validPrescriptions = false;
            }
        });
        
        if (!validPrescriptions) {
            e.preventDefault();
            alert('Harap isi aturan pakai untuk semua obat yang dipilih.');
            return;
        }
        
        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.disabled = true;
    });
</script>
@endsection