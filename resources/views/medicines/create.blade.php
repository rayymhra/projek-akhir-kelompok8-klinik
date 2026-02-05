@extends('layouts.app')

@section('title', 'Tambah Data Obat Baru')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-pills me-2"></i>Tambah Data Obat Baru
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('medicines.store') }}" id="medicineForm">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Dasar Obat
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="nama_obat" class="form-label">Nama Obat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_obat') is-invalid @enderror" 
                                   id="nama_obat" name="nama_obat" value="{{ old('nama_obat') }}" required 
                                   placeholder="Contoh: Paracetamol 500mg">
                            @error('nama_obat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="kode_obat" class="form-label">Kode Obat <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('kode_obat') is-invalid @enderror" 
                                       id="kode_obat" name="kode_obat" value="{{ old('kode_obat') }}" required 
                                       placeholder="Contoh: PAR-500">
                                <button class="btn btn-outline-secondary" type="button" onclick="generateMedicineCode()">
                                    <i class="fas fa-barcode"></i> Generate
                                </button>
                            </div>
                            <div class="form-text">Kode unik untuk identifikasi obat</div>
                            @error('kode_obat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jenis_obat" class="form-label">Jenis Obat <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_obat') is-invalid @enderror" 
                                    id="jenis_obat" name="jenis_obat" required>
                                <option value="">Pilih Jenis Obat...</option>
                                <option value="Tablet" {{ old('jenis_obat') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Kapsul" {{ old('jenis_obat') == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                                <option value="Sirup" {{ old('jenis_obat') == 'Sirup' ? 'selected' : '' }}>Sirup</option>
                                <option value="Salep" {{ old('jenis_obat') == 'Salep' ? 'selected' : '' }}>Salep</option>
                                <option value="Krim" {{ old('jenis_obat') == 'Krim' ? 'selected' : '' }}>Krim</option>
                                <option value="Injeksi" {{ old('jenis_obat') == 'Injeksi' ? 'selected' : '' }}>Injeksi</option>
                                <option value="Drops" {{ old('jenis_obat') == 'Drops' ? 'selected' : '' }}>Drops</option>
                                <option value="Inhaler" {{ old('jenis_obat') == 'Inhaler' ? 'selected' : '' }}>Inhaler</option>
                                <option value="Suppositoria" {{ old('jenis_obat') == 'Suppositoria' ? 'selected' : '' }}>Suppositoria</option>
                                <option value="Lainnya" {{ old('jenis_obat') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('jenis_obat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                            <select class="form-select @error('satuan') is-invalid @enderror" 
                                    id="satuan" name="satuan" required>
                                <option value="">Pilih Satuan...</option>
                                <option value="Tablet" {{ old('satuan') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Kapsul" {{ old('satuan') == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                                <option value="Botol" {{ old('satuan') == 'Botol' ? 'selected' : '' }}>Botol</option>
                                <option value="Tube" {{ old('satuan') == 'Tube' ? 'selected' : '' }}>Tube</option>
                                <option value="Ampul" {{ old('satuan') == 'Ampul' ? 'selected' : '' }}>Ampul</option>
                                <option value="Vial" {{ old('satuan') == 'Vial' ? 'selected' : '' }}>Vial</option>
                                <option value="Strip" {{ old('satuan') == 'Strip' ? 'selected' : '' }}>Strip</option>
                                <option value="Sachet" {{ old('satuan') == 'Sachet' ? 'selected' : '' }}>Sachet</option>
                                <option value="Pcs" {{ old('satuan') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                            </select>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Stock & Price -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-boxes me-2"></i>Stok & Harga
                            </h6>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="stok" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                       id="stok" name="stok" value="{{ old('stok', 0) }}" required 
                                       min="0" step="1">
                                <span class="input-group-text" id="satuanDisplay">Pcs</span>
                            </div>
                            <div class="form-text">Jumlah stok awal obat</div>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="stok_minimum" class="form-label">Stok Minimum</label>
                            <input type="number" class="form-control @error('stok_minimum') is-invalid @enderror" 
                                   id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', 10) }}" 
                                   min="1" step="1">
                            <div class="form-text">Peringatan saat stok mencapai jumlah ini</div>
                            @error('stok_minimum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="harga" class="form-label">Harga Satuan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                       id="harga" name="harga" value="{{ old('harga') }}" required 
                                       min="0" step="100">
                            </div>
                            <div class="form-text">Harga per satuan obat</div>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Total Value Display -->
                        <div class="col-12 mt-2">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-0">Nilai Stok:</h6>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <h5 class="mb-0 text-primary" id="stockValue">Rp 0</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Expiry & Supplier -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Tanggal Kadaluwarsa & Supplier
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="expired_date" class="form-label">Tanggal Kadaluwarsa</label>
                            <input type="date" class="form-control @error('expired_date') is-invalid @enderror" 
                                   id="expired_date" name="expired_date" 
                                   value="{{ old('expired_date') }}">
                            <div class="form-text">Kosongkan jika tidak ada tanggal kadaluwarsa</div>
                            @error('expired_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <span class="badge" id="expiryStatus"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="supplier" class="form-label">Supplier (Opsional)</label>
                            <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                   id="supplier" name="supplier" value="{{ old('supplier') }}" 
                                   placeholder="Nama supplier obat">
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="batch_number" class="form-label">No. Batch (Opsional)</label>
                            <input type="text" class="form-control @error('batch_number') is-invalid @enderror" 
                                   id="batch_number" name="batch_number" value="{{ old('batch_number') }}" 
                                   placeholder="Nomor batch/lot">
                            @error('batch_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="lokasi_penyimpanan" class="form-label">Lokasi Penyimpanan</label>
                            <select class="form-select @error('lokasi_penyimpanan') is-invalid @enderror" 
                                    id="lokasi_penyimpanan" name="lokasi_penyimpanan">
                                <option value="">Pilih Lokasi...</option>
                                <option value="Rak A" {{ old('lokasi_penyimpanan') == 'Rak A' ? 'selected' : '' }}>Rak A</option>
                                <option value="Rak B" {{ old('lokasi_penyimpanan') == 'Rak B' ? 'selected' : '' }}>Rak B</option>
                                <option value="Rak C" {{ old('lokasi_penyimpanan') == 'Rak C' ? 'selected' : '' }}>Rak C</option>
                                <option value="Lemari Es" {{ old('lokasi_penyimpanan') == 'Lemari Es' ? 'selected' : '' }}>Lemari Es</option>
                                <option value="Gudang" {{ old('lokasi_penyimpanan') == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                                <option value="Lainnya" {{ old('lokasi_penyimpanan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('lokasi_penyimpanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-file-alt me-2"></i>Informasi Tambahan
                            </h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="golongan" class="form-label">Golongan Obat</label>
                            <select class="form-select @error('golongan') is-invalid @enderror" 
                                    id="golongan" name="golongan">
                                <option value="">Pilih Golongan...</option>
                                <option value="Bebas" {{ old('golongan') == 'Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                                <option value="Bebas Terbatas" {{ old('golongan') == 'Bebas Terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas</option>
                                <option value="Keras" {{ old('golongan') == 'Keras' ? 'selected' : '' }}>Obat Keras</option>
                                <option value="Psikotropika" {{ old('golongan') == 'Psikotropika' ? 'selected' : '' }}>Psikotropika</option>
                                <option value="Narkotika" {{ old('golongan') == 'Narkotika' ? 'selected' : '' }}>Narkotika</option>
                                <option value="Fitofarmaka" {{ old('golongan') == 'Fitofarmaka' ? 'selected' : '' }}>Fitofarmaka</option>
                                <option value="Herbal" {{ old('golongan') == 'Herbal' ? 'selected' : '' }}>Herbal</option>
                            </select>
                            @error('golongan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select @error('kategori') is-invalid @enderror" 
                                    id="kategori" name="kategori">
                                <option value="">Pilih Kategori...</option>
                                <option value="Analgesik" {{ old('kategori') == 'Analgesik' ? 'selected' : '' }}>Analgesik</option>
                                <option value="Antibiotik" {{ old('kategori') == 'Antibiotik' ? 'selected' : '' }}>Antibiotik</option>
                                <option value="Antihipertensi" {{ old('kategori') == 'Antihipertensi' ? 'selected' : '' }}>Antihipertensi</option>
                                <option value="Antidiabetik" {{ old('kategori') == 'Antidiabetik' ? 'selected' : '' }}>Antidiabetik</option>
                                <option value="Antiasma" {{ old('kategori') == 'Antiasma' ? 'selected' : '' }}>Antiasma</option>
                                <option value="Vitamin" {{ old('kategori') == 'Vitamin' ? 'selected' : '' }}>Vitamin & Suplemen</option>
                                <option value="Lainnya" {{ old('kategori') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3" 
                                      placeholder="Deskripsi obat, indikasi, kontraindikasi, dll.">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    {{-- <!-- Quick Add for Multiple Medicines -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-bolt me-2"></i>Tambah Cepat (Multiple)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Fitur ini untuk menambahkan beberapa obat sekaligus dengan data yang sama.
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Jumlah obat yang akan ditambahkan</label>
                                            <input type="number" class="form-control" id="multipleCount" 
                                                   min="2" max="10" value="2">
                                        </div>
                                        <div class="col-md-6 mb-3 d-flex align-items-end">
                                            <button type="button" class="btn btn-outline-primary w-100" 
                                                    onclick="addMultipleMedicines()">
                                                <i class="fas fa-copy me-2"></i>Buat Form Tambahan
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div id="multipleFormsContainer"></div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('medicines.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Simpan Obat
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Medicine Code Template -->
<template id="medicineCodeTemplate">
    <div class="alert alert-info">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>Kode yang digenerate:</strong>
                <span id="generatedCode" class="ms-2"></span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-success" onclick="useGeneratedCode()">
                <i class="fas fa-check me-2"></i>Gunakan
            </button>
        </div>
    </div>
</template>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('medicineForm');
        const submitBtn = document.getElementById('submitBtn');
        const namaObatInput = document.getElementById('nama_obat');
        const kodeObatInput = document.getElementById('kode_obat');
        const jenisObatSelect = document.getElementById('jenis_obat');
        const satuanSelect = document.getElementById('satuan');
        const satuanDisplay = document.getElementById('satuanDisplay');
        const stokInput = document.getElementById('stok');
        const hargaInput = document.getElementById('harga');
        const stockValueDisplay = document.getElementById('stockValue');
        const expiredDateInput = document.getElementById('expired_date');
        const expiryStatusBadge = document.getElementById('expiryStatus');
        
        // Update satuan display when satuan changes
        satuanSelect.addEventListener('change', function() {
            satuanDisplay.textContent = this.value;
        });
        
        // Calculate stock value
        function calculateStockValue() {
            const stok = parseInt(stokInput.value) || 0;
            const harga = parseInt(hargaInput.value) || 0;
            const totalValue = stok * harga;
            
            stockValueDisplay.textContent = formatCurrency(totalValue);
            
            // Update stock warning
            const stokMinimum = parseInt(document.getElementById('stok_minimum').value) || 10;
            if (stok <= stokMinimum) {
                stockValueDisplay.className = 'mb-0 text-danger';
            } else {
                stockValueDisplay.className = 'mb-0 text-primary';
            }
        }
        
        // Check expiry date status
        function checkExpiryStatus() {
            const expiryDate = expiredDateInput.value;
            if (!expiryDate) {
                expiryStatusBadge.textContent = '';
                expiryStatusBadge.className = 'badge';
                return;
            }
            
            const today = new Date();
            const expiry = new Date(expiryDate);
            const daysUntilExpiry = Math.floor((expiry - today) / (1000 * 60 * 60 * 24));
            
            let text = '';
            let badgeClass = '';
            
            if (daysUntilExpiry < 0) {
                text = 'KADALUWARSA';
                badgeClass = 'badge bg-danger';
            } else if (daysUntilExpiry <= 30) {
                text = 'Hampir Kadaluwarsa';
                badgeClass = 'badge bg-warning';
            } else if (daysUntilExpiry <= 90) {
                text = 'Periksa Segera';
                badgeClass = 'badge bg-info';
            } else {
                text = 'Aman';
                badgeClass = 'badge bg-success';
            }
            
            expiryStatusBadge.textContent = `${text} (${Math.abs(daysUntilExpiry)} hari)`;
            expiryStatusBadge.className = badgeClass;
        }
        
        // Generate medicine code
        function generateMedicineCode() {
            const medicineName = namaObatInput.value;
            const medicineType = jenisObatSelect.value;
            
            if (!medicineName.trim()) {
                showAlert('Masukkan nama obat terlebih dahulu', 'warning');
                return;
            }
            
            // Generate code based on name and type
            let code = '';
            
            // Take first 3 letters of medicine name in uppercase
            const namePart = medicineName.substring(0, 3).toUpperCase();
            
            // Add type code
            const typeCodes = {
                'Tablet': 'TAB',
                'Kapsul': 'KAP',
                'Sirup': 'SIR',
                'Salep': 'SAL',
                'Krim': 'KRM',
                'Injeksi': 'INJ',
                'Drops': 'DRP',
                'Inhaler': 'INH',
                'Suppositoria': 'SUP',
                'Lainnya': 'OTH'
            };
            
            const typeCode = typeCodes[medicineType] || 'MED';
            
            // Generate random number
            const randomNum = Math.floor(100 + Math.random() * 900);
            
            code = `${typeCode}-${namePart}-${randomNum}`;
            
            // Show code in template
            const template = document.getElementById('medicineCodeTemplate');
            const codeAlert = template.content.cloneNode(true);
            
            // Remove existing code alert if any
            const existingAlert = document.querySelector('.alert-info[data-code-alert]');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Update and insert new alert
            const alertDiv = codeAlert.querySelector('.alert');
            alertDiv.setAttribute('data-code-alert', 'true');
            alertDiv.querySelector('#generatedCode').textContent = code;
            
            // Insert after kode_obat input group
            kodeObatInput.parentElement.parentElement.insertAdjacentElement('afterend', alertDiv);
        }
        
        // Use generated code
        function useGeneratedCode() {
            const generatedCode = document.querySelector('#generatedCode').textContent;
            kodeObatInput.value = generatedCode;
            
            // Remove the alert
            const alertDiv = document.querySelector('.alert-info[data-code-alert]');
            if (alertDiv) {
                alertDiv.remove();
            }
        }
        
        // Add multiple medicines
        function addMultipleMedicines() {
            const count = parseInt(document.getElementById('multipleCount').value) || 2;
            const container = document.getElementById('multipleFormsContainer');
            
            if (count < 2 || count > 10) {
                showAlert('Jumlah obat harus antara 2-10', 'warning');
                return;
            }
            
            container.innerHTML = '';
            
            for (let i = 1; i < count; i++) {
                const formDiv = document.createElement('div');
                formDiv.className = 'card mt-3';
                formDiv.innerHTML = `
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Obat ${i + 1}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Obat</label>
                                <input type="text" class="form-control" name="medicines[${i}][nama_obat]" 
                                       placeholder="Nama obat ${i + 1}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kode Obat</label>
                                <input type="text" class="form-control" name="medicines[${i}][kode_obat]" 
                                       placeholder="Kode obat ${i + 1}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jenis</label>
                                <select class="form-select" name="medicines[${i}][jenis_obat]" required>
                                    <option value="">Pilih...</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Kapsul">Kapsul</option>
                                    <option value="Sirup">Sirup</option>
                                    <option value="Salep">Salep</option>
                                    <option value="Krim">Krim</option>
                                    <option value="Injeksi">Injeksi</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control" name="medicines[${i}][stok]" 
                                       value="0" min="0" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="medicines[${i}][harga]" 
                                           min="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                container.appendChild(formDiv);
            }
            
            // Update form action and method for multiple submission
            form.setAttribute('action', '{{ route("medicines.store") }}');
            form.innerHTML += '<input type="hidden" name="multiple" value="true">';
        }
        
        // Form validation before submit
        form.addEventListener('submit', function(e) {
            // Validate stock and price
            const stok = parseInt(stokInput.value);
            const harga = parseInt(hargaInput.value);
            const stokMinimum = parseInt(document.getElementById('stok_minimum').value) || 0;
            
            if (stok < 0) {
                e.preventDefault();
                showAlert('Stok tidak boleh negatif', 'danger');
                stokInput.focus();
                return;
            }
            
            if (harga < 0) {
                e.preventDefault();
                showAlert('Harga tidak boleh negatif', 'danger');
                hargaInput.focus();
                return;
            }
            
            if (stokMinimum < 0) {
                e.preventDefault();
                showAlert('Stok minimum tidak boleh negatif', 'danger');
                document.getElementById('stok_minimum').focus();
                return;
            }
            
            // Check if kode obat already exists
            const kodeObat = kodeObatInput.value;
            if (kodeObat) {
                // You can add AJAX check here for existing code
                // For now, just basic validation
                if (kodeObat.length < 3) {
                    e.preventDefault();
                    showAlert('Kode obat minimal 3 karakter', 'danger');
                    kodeObatInput.focus();
                    return;
                }
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });
        
        // Utility Functions
        function formatCurrency(amount) {
            return 'Rp ' + amount.toLocaleString('id-ID');
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
        
        // Event listeners
        stokInput.addEventListener('input', calculateStockValue);
        hargaInput.addEventListener('input', calculateStockValue);
        expiredDateInput.addEventListener('change', checkExpiryStatus);
        
        // Auto-generate code on nama obat change
        namaObatInput.addEventListener('blur', function() {
            if (!kodeObatInput.value && this.value) {
                generateMedicineCode();
            }
        });
        
        // Initialize
        satuanDisplay.textContent = satuanSelect.value;
        calculateStockValue();
        checkExpiryStatus();
        
        // Make functions global
        window.generateMedicineCode = generateMedicineCode;
        window.useGeneratedCode = useGeneratedCode;
        window.addMultipleMedicines = addMultipleMedicines;
    });
</script>
@endsection