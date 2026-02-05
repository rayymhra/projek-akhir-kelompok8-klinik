@extends('layouts.app')

@section('title', 'Edit Data Obat')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow">
            <div class="card-header py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-pills me-2"></i>Edit Data Obat
                    </h5>
                    <div>
                        <span class="badge bg-secondary">{{ $medicine->kode_obat }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('medicines.update', $medicine) }}" id="medicineForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Medicine Info Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                    <div>
                                        <strong>Perhatian:</strong> Perubahan stok dan harga akan mempengaruhi 
                                        semua transaksi dan resep yang menggunakan obat ini.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
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
                                   id="nama_obat" name="nama_obat" 
                                   value="{{ old('nama_obat', $medicine->nama_obat) }}" required>
                            @error('nama_obat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="kode_obat" class="form-label">Kode Obat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_obat') is-invalid @enderror" 
                                   id="kode_obat" name="kode_obat" 
                                   value="{{ old('kode_obat', $medicine->kode_obat) }}" required readonly>
                            <div class="form-text">Kode obat tidak dapat diubah</div>
                            @error('kode_obat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="jenis_obat" class="form-label">Jenis Obat <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_obat') is-invalid @enderror" 
                                    id="jenis_obat" name="jenis_obat" required>
                                <option value="">Pilih Jenis Obat...</option>
                                <option value="Tablet" {{ old('jenis_obat', $medicine->jenis_obat) == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Kapsul" {{ old('jenis_obat', $medicine->jenis_obat) == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                                <option value="Sirup" {{ old('jenis_obat', $medicine->jenis_obat) == 'Sirup' ? 'selected' : '' }}>Sirup</option>
                                <option value="Salep" {{ old('jenis_obat', $medicine->jenis_obat) == 'Salep' ? 'selected' : '' }}>Salep</option>
                                <option value="Krim" {{ old('jenis_obat', $medicine->jenis_obat) == 'Krim' ? 'selected' : '' }}>Krim</option>
                                <option value="Injeksi" {{ old('jenis_obat', $medicine->jenis_obat) == 'Injeksi' ? 'selected' : '' }}>Injeksi</option>
                                <option value="Drops" {{ old('jenis_obat', $medicine->jenis_obat) == 'Drops' ? 'selected' : '' }}>Drops</option>
                                <option value="Inhaler" {{ old('jenis_obat', $medicine->jenis_obat) == 'Inhaler' ? 'selected' : '' }}>Inhaler</option>
                                <option value="Suppositoria" {{ old('jenis_obat', $medicine->jenis_obat) == 'Suppositoria' ? 'selected' : '' }}>Suppositoria</option>
                                <option value="Lainnya" {{ old('jenis_obat', $medicine->jenis_obat) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                <option value="Tablet" {{ old('satuan', $medicine->satuan) == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="Kapsul" {{ old('satuan', $medicine->satuan) == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                                <option value="Botol" {{ old('satuan', $medicine->satuan) == 'Botol' ? 'selected' : '' }}>Botol</option>
                                <option value="Tube" {{ old('satuan', $medicine->satuan) == 'Tube' ? 'selected' : '' }}>Tube</option>
                                <option value="Ampul" {{ old('satuan', $medicine->satuan) == 'Ampul' ? 'selected' : '' }}>Ampul</option>
                                <option value="Vial" {{ old('satuan', $medicine->satuan) == 'Vial' ? 'selected' : '' }}>Vial</option>
                                <option value="Strip" {{ old('satuan', $medicine->satuan) == 'Strip' ? 'selected' : '' }}>Strip</option>
                                <option value="Sachet" {{ old('satuan', $medicine->satuan) == 'Sachet' ? 'selected' : '' }}>Sachet</option>
                                <option value="Pcs" {{ old('satuan', $medicine->satuan) == 'Pcs' ? 'selected' : '' }}>Pcs</option>
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
                            <label for="stok" class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                       id="stok" name="stok" 
                                       value="{{ old('stok', $medicine->stok) }}" required min="0" step="1">
                                <span class="input-group-text" id="satuanDisplay">{{ $medicine->satuan }}</span>
                            </div>
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="stok_minimum" class="form-label">Stok Minimum</label>
                            <input type="number" class="form-control @error('stok_minimum') is-invalid @enderror" 
                                   id="stok_minimum" name="stok_minimum" 
                                   value="{{ old('stok_minimum', $medicine->stok_minimum ?? 10) }}" min="1" step="1">
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
                                       id="harga" name="harga" 
                                       value="{{ old('harga', $medicine->harga) }}" required min="0" step="100">
                            </div>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Stock Adjustment -->
                        <div class="col-12 mb-3">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="fas fa-exchange-alt me-2"></i>Penyesuaian Stok
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Tambah/ Kurangi Stok</label>
                                            <div class="input-group">
                                                <select class="form-select" id="adjustmentType">
                                                    <option value="add">Tambah</option>
                                                    <option value="subtract">Kurangi</option>
                                                </select>
                                                <input type="number" class="form-control" id="adjustmentAmount" 
                                                       min="1" value="1">
                                                <button class="btn btn-outline-primary" type="button" 
                                                        onclick="applyStockAdjustment()">
                                                    Terapkan
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Stok saat ini:</strong> {{ $medicine->stok }} {{ $medicine->satuan }}
                                                <br>
                                                <strong>Status:</strong> 
                                                @if($medicine->stok == 0)
                                                    <span class="badge bg-danger">Habis</span>
                                                @elseif($medicine->stok <= 10)
                                                    <span class="badge bg-warning">Rendah</span>
                                                @else
                                                    <span class="badge bg-success">Aman</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Value Display -->
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body py-2">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-0">Nilai Stok:</h6>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            @php
                                                $stockValue = $medicine->stok * $medicine->harga;
                                            @endphp
                                            <h5 class="mb-0 text-primary" id="stockValue">
                                                Rp {{ number_format($stockValue, 0, ',', '.') }}
                                            </h5>
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
                                   value="{{ old('expired_date', $medicine->expired_date ? $medicine->expired_date->format('Y-m-d') : '') }}">
                            @error('expired_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                @if($medicine->expired_date)
                                    @php
                                        $today = now();
                                        $expiryDate = $medicine->expired_date;
                                        $daysUntilExpiry = $today->diffInDays($expiryDate, false);
                                        
                                        if ($daysUntilExpiry < 0) {
                                            $badgeClass = 'bg-danger';
                                            $badgeText = 'KADALUWARSA';
                                        } elseif ($daysUntilExpiry <= 30) {
                                            $badgeClass = 'bg-warning';
                                            $badgeText = 'Hampir Kadaluwarsa';
                                        } elseif ($daysUntilExpiry <= 90) {
                                            $badgeClass = 'bg-info';
                                            $badgeText = 'Periksa Segera';
                                        } else {
                                            $badgeClass = 'bg-success';
                                            $badgeText = 'Aman';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}" id="expiryStatus">
                                        {{ $badgeText }} ({{ abs($daysUntilExpiry) }} hari)
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="supplier" class="form-label">Supplier</label>
                            <input type="text" class="form-control @error('supplier') is-invalid @enderror" 
                                   id="supplier" name="supplier" 
                                   value="{{ old('supplier', $medicine->supplier) }}">
                            @error('supplier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="batch_number" class="form-label">No. Batch</label>
                            <input type="text" class="form-control @error('batch_number') is-invalid @enderror" 
                                   id="batch_number" name="batch_number" 
                                   value="{{ old('batch_number', $medicine->batch_number) }}">
                            @error('batch_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="lokasi_penyimpanan" class="form-label">Lokasi Penyimpanan</label>
                            <select class="form-select @error('lokasi_penyimpanan') is-invalid @enderror" 
                                    id="lokasi_penyimpanan" name="lokasi_penyimpanan">
                                <option value="">Pilih Lokasi...</option>
                                <option value="Rak A" {{ old('lokasi_penyimpanan', $medicine->lokasi_penyimpanan) == 'Rak A' ? 'selected' : '' }}>Rak A</option>
                                <option value="Rak B" {{ old('lokasi_penyimpanan', $medicine->lokasi_penyimpanan) == 'Rak B' ? 'selected' : '' }}>Rak B</option>
                                <option value="Rak C" {{ old('lokasi_penyimpanan', $medicine->lokasi_penyimpanan) == 'Rak C' ? 'selected' : '' }}>Rak C</option>
                                <option value="Lemari Es" {{ old('lokasi_penyimpanan', $medicine->lokasi_penyimpanan) == 'Lemari Es' ? 'selected' : '' }}>Lemari Es</option>
                                <option value="Gudang" {{ old('lokasi_penyimpanan', $medicine->lokasi_penyimpanan) == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                                <option value="Lainnya" {{ old('lokasi_penyimpanan', $medicine->lokasi_penyimpanan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                <option value="Bebas" {{ old('golongan', $medicine->golongan) == 'Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                                <option value="Bebas Terbatas" {{ old('golongan', $medicine->golongan) == 'Bebas Terbatas' ? 'selected' : '' }}>Obat Bebas Terbatas</option>
                                <option value="Keras" {{ old('golongan', $medicine->golongan) == 'Keras' ? 'selected' : '' }}>Obat Keras</option>
                                <option value="Psikotropika" {{ old('golongan', $medicine->golongan) == 'Psikotropika' ? 'selected' : '' }}>Psikotropika</option>
                                <option value="Narkotika" {{ old('golongan', $medicine->golongan) == 'Narkotika' ? 'selected' : '' }}>Narkotika</option>
                                <option value="Fitofarmaka" {{ old('golongan', $medicine->golongan) == 'Fitofarmaka' ? 'selected' : '' }}>Fitofarmaka</option>
                                <option value="Herbal" {{ old('golongan', $medicine->golongan) == 'Herbal' ? 'selected' : '' }}>Herbal</option>
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
                                <option value="Analgesik" {{ old('kategori', $medicine->kategori) == 'Analgesik' ? 'selected' : '' }}>Analgesik</option>
                                <option value="Antibiotik" {{ old('kategori', $medicine->kategori) == 'Antibiotik' ? 'selected' : '' }}>Antibiotik</option>
                                <option value="Antihipertensi" {{ old('kategori', $medicine->kategori) == 'Antihipertensi' ? 'selected' : '' }}>Antihipertensi</option>
                                <option value="Antidiabetik" {{ old('kategori', $medicine->kategori) == 'Antidiabetik' ? 'selected' : '' }}>Antidiabetik</option>
                                <option value="Antiasma" {{ old('kategori', $medicine->kategori) == 'Antiasma' ? 'selected' : '' }}>Antiasma</option>
                                <option value="Vitamin" {{ old('kategori', $medicine->kategori) == 'Vitamin' ? 'selected' : '' }}>Vitamin & Suplemen</option>
                                <option value="Lainnya" {{ old('kategori', $medicine->kategori) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $medicine->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Medicine Statistics -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Statistik Obat
                            </h6>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Total Resep</h6>
                                    <h5 class="mb-0">{{ $medicine->prescriptions_count ?? $medicine->prescriptions()->count() }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Digunakan Bulan Ini</h6>
                                    @php
                                        $monthlyUsage = $medicine->prescriptions()
                                            ->whereMonth('created_at', date('m'))
                                            ->whereYear('created_at', date('Y'))
                                            ->sum('jumlah');
                                    @endphp
                                    <h5 class="mb-0">{{ $monthlyUsage }}</h5>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Status Stok</h6>
                                    @if($medicine->stok == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($medicine->stok <= 10)
                                        <span class="badge bg-warning">Rendah</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card bg-light">
                                <div class="card-body text-center py-3">
                                    <h6 class="mb-1">Diperbarui</h6>
                                    <h5 class="mb-0">{{ $medicine->updated_at->format('d/m/Y') }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Usage -->
                    @if($medicine->prescriptions()->count() > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fas fa-history me-2"></i>Penggunaan Terakhir
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Pasien</th>
                                                    <th>Dokter</th>
                                                    <th>Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($medicine->prescriptions()->with(['medicalRecord.visit.patient', 'medicalRecord.visit.doctor'])->latest()->take(5)->get() as $prescription)
                                                <tr>
                                                    <td>{{ $prescription->created_at->format('d/m/Y') }}</td>
                                                    <td>{{ $prescription->medicalRecord->visit->patient->nama }}</td>
                                                    <td>{{ $prescription->medicalRecord->visit->doctor->name }}</td>
                                                    <td>{{ $prescription->jumlah }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ route('medicines.show', $medicine) }}" class="btn btn-secondary w-100">
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
        const form = document.getElementById('medicineForm');
        const submitBtn = document.getElementById('submitBtn');
        const stokInput = document.getElementById('stok');
        const hargaInput = document.getElementById('harga');
        const stockValueDisplay = document.getElementById('stockValue');
        const satuanSelect = document.getElementById('satuan');
        const satuanDisplay = document.getElementById('satuanDisplay');
        const expiredDateInput = document.getElementById('expired_date');
        const adjustmentTypeSelect = document.getElementById('adjustmentType');
        const adjustmentAmountInput = document.getElementById('adjustmentAmount');
        
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
        
        // Apply stock adjustment
        function applyStockAdjustment() {
            const currentStock = parseInt(stokInput.value) || 0;
            const adjustmentType = adjustmentTypeSelect.value;
            const adjustmentAmount = parseInt(adjustmentAmountInput.value) || 0;
            
            if (adjustmentAmount <= 0) {
                showAlert('Jumlah penyesuaian harus lebih dari 0', 'warning');
                return;
            }
            
            let newStock = currentStock;
            
            if (adjustmentType === 'add') {
                newStock = currentStock + adjustmentAmount;
            } else if (adjustmentType === 'subtract') {
                newStock = currentStock - adjustmentAmount;
                
                if (newStock < 0) {
                    showAlert('Stok tidak boleh negatif', 'danger');
                    return;
                }
            }
            
            stokInput.value = newStock;
            calculateStockValue();
            
            // Show confirmation
            showAlert(`Stok berhasil disesuaikan: ${currentStock} → ${newStock}`, 'success');
        }
        
        // Check expiry date status
        function checkExpiryStatus() {
            const expiryDate = expiredDateInput.value;
            if (!expiryDate) {
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
            
            const expiryStatusBadge = document.getElementById('expiryStatus');
            if (expiryStatusBadge) {
                expiryStatusBadge.textContent = `${text} (${Math.abs(daysUntilExpiry)} hari)`;
                expiryStatusBadge.className = badgeClass;
            }
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
            
            // Check expiry date if provided
            const expiryDate = expiredDateInput.value;
            if (expiryDate) {
                const today = new Date();
                const expiry = new Date(expiryDate);
                
                if (expiry < today) {
                    if (!confirm('Obat ini sudah kadaluwarsa. Lanjutkan penyimpanan?')) {
                        e.preventDefault();
                        return;
                    }
                }
            }
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            submitBtn.disabled = true;
        });
        
        // Reset form
        function resetForm() {
            if (confirm('Reset semua perubahan?')) {
                form.reset();
                calculateStockValue();
                checkExpiryStatus();
            }
        }
        
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
        
        // Initialize
        satuanDisplay.textContent = satuanSelect.value;
        calculateStockValue();
        checkExpiryStatus();
        
        // Make functions global
        window.applyStockAdjustment = applyStockAdjustment;
        window.resetForm = resetForm;
    });
</script>
@endsection