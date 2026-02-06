@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-edit text-warning"></i> Edit Transaksi</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transaksi</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transactions.show', $transaction) }}">Detail</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </div>

    <!-- Warning Alert -->
    @if($transaction->status === 'lunas')
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Perhatian!</strong> Anda sedang mengedit transaksi yang sudah lunas. Perubahan akan dicatat dalam log sistem.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Main Form -->
    <form method="POST" action="{{ route('transactions.update', $transaction) }}" id="editForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Informasi Pasien (Read-only) -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-user-injured me-2"></i> Informasi Pasien</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Pasien</label>
                            <p class="fs-5 fw-bold">{{ $transaction->visit->patient->nama }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">No. Rekam Medis</label>
                            <p class="fs-6">
                                <span class="badge bg-secondary">{{ $transaction->visit->patient->no_rekam_medis }}</span>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Dokter</label>
                            <p class="fs-6">
                                <i class="fas fa-user-md text-primary me-2"></i>
                                {{ $transaction->visit->doctor->name }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Tanggal Kunjungan</label>
                            <p class="fs-6">{{ $transaction->visit->tanggal_kunjungan->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Status Saat Ini</label>
                            <div class="status-badge status-{{ $transaction->status }}">
                                {{ strtoupper($transaction->status) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembayaran -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i> Informasi Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <!-- Metode Pembayaran -->
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label fw-bold">Metode Pembayaran *</label>
                            <select class="form-select @error('metode_pembayaran') is-invalid @enderror" 
                                    id="metode_pembayaran" name="metode_pembayaran" required
                                    onchange="toggleProofSection()">
                                <option value="tunai" {{ old('metode_pembayaran', $transaction->metode_pembayaran) == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ old('metode_pembayaran', $transaction->metode_pembayaran) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="qris" {{ old('metode_pembayaran', $transaction->metode_pembayaran) == 'qris' ? 'selected' : '' }}>QRIS</option>
                                <option value="e-wallet" {{ old('metode_pembayaran', $transaction->metode_pembayaran) == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status Transaksi -->
                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Status Transaksi *</label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required onchange="checkPaidAmount()">
                                <option value="menunggu" {{ old('status', $transaction->status) == 'menunggu' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="lunas" {{ old('status', $transaction->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                <option value="batal" {{ old('status', $transaction->status) == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bukti Pembayaran -->
                        <div class="mb-3" id="proofSection" style="{{ $transaction->metode_pembayaran == 'tunai' ? 'display: none;' : '' }}">
                            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                            
                            @if($transaction->bukti_pembayaran)
                            <div class="mb-2">
                                <p class="mb-1">Bukti saat ini:</p>
                                @if(Str::endsWith($transaction->bukti_pembayaran, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ Storage::url($transaction->bukti_pembayaran) }}" 
                                         alt="Bukti Pembayaran" 
                                         class="img-thumbnail" 
                                         style="max-height: 100px;">
                                @else
                                    <div class="alert alert-info p-2">
                                        <i class="fas fa-file-pdf"></i> File bukti pembayaran
                                        <a href="{{ Storage::url($transaction->bukti_pembayaran) }}" 
                                           target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </div>
                                @endif
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="hapus_bukti" id="hapus_bukti" value="1">
                                    <label class="form-check-label text-danger" for="hapus_bukti">
                                        <i class="fas fa-trash"></i> Hapus bukti ini
                                    </label>
                                </div>
                            </div>
                            @endif
                            
                            <input type="file" class="form-control @error('bukti_pembayaran') is-invalid @enderror" 
                                   id="bukti_pembayaran" name="bukti_pembayaran" 
                                   accept="image/*,.pdf">
                            @error('bukti_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Unggah bukti baru (maks. 2MB, format: jpg, png, pdf)</div>
                            <div id="proofPreview" class="mt-2"></div>
                        </div>

                        <!-- Catatan Perubahan -->
                        <div class="mb-3">
                            <label for="alasan_edit" class="form-label">Alasan Perubahan</label>
                            <textarea class="form-control" id="alasan_edit" name="alasan_edit" 
                                      rows="2" placeholder="Jelaskan alasan perubahan transaksi...">{{ old('alasan_edit') }}</textarea>
                            <div class="form-text">Opsional: Catatan untuk audit trail</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ringkasan -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i> Ringkasan</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Total Item</label>
                            <h4 id="totalItems">{{ $transaction->details->count() }} item</h4>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted">Total Biaya</label>
                            <h2 class="text-success" id="totalAmount">
                                Rp {{ number_format($transaction->details->sum('subtotal'), 0, ',', '.') }}
                            </h2>
                        </div>

                        <hr>

                        <!-- Jumlah Dibayar -->
                        <div class="mb-3">
                            <label for="jumlah_dibayar" class="form-label fw-bold">Jumlah Dibayar (Rp) *</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('jumlah_dibayar') is-invalid @enderror" 
                                       id="jumlah_dibayar" name="jumlah_dibayar" 
                                       value="{{ old('jumlah_dibayar', $transaction->jumlah_dibayar ?? $transaction->total_biaya) }}"
                                       min="0" step="1000" required
                                       onchange="calculateChange()" onkeyup="calculateChange()">
                            </div>
                            @error('jumlah_dibayar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Kembalian -->
                        <div class="mb-3">
                            <label class="form-label">Kembalian</label>
                            <div class="alert alert-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Kembalian:</span>
                                    <span id="changeAmount" class="fs-4">Rp 0</span>
                                </div>
                                <div id="changeMessage" class="mt-2 small"></div>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle"></i> 
                                Total biaya otomatis dihitung dari item transaksi.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Transaksi -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Item Transaksi</h5>
                        <button type="button" class="btn btn-light btn-sm" onclick="addItem()">
                            <i class="fas fa-plus"></i> Tambah Item
                        </button>
                    </div>
                    <div class="card-body">
                        <!-- Warning for existing transaction -->
                        @if($transaction->status === 'lunas')
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Perhatian!</strong> Mengubah item pada transaksi lunas akan mempengaruhi stok obat.
                            Pastikan perubahan sudah benar.
                        </div>
                        @endif

                        <!-- Items Container -->
                        <div id="itemsContainer">
                            @foreach($transaction->details as $index => $detail)
                            <div class="item-row mb-3 p-3 border rounded bg-light" data-index="{{ $index }}">
                                <div class="row">
                                    <!-- Hidden fields -->
                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $detail->id }}">
                                    
                                    <!-- Jenis Item -->
                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">Jenis Item</label>
                                        <select class="form-select item-type" name="items[{{ $index }}][type]" 
                                                onchange="updateItemFields(this)" required>
                                            <option value="medicine" {{ $detail->item_type == 'medicine' ? 'selected' : '' }}>Obat</option>
                                            <option value="service" {{ $detail->item_type == 'service' ? 'selected' : '' }}>Layanan</option>
                                            <option value="other" {{ $detail->item_type == 'other' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    <!-- Selector based on type -->
                                    <div class="col-md-3 mb-2 item-select" style="{{ $detail->item_type == 'other' ? 'display: none;' : '' }}">
                                        <label class="form-label">Pilih Item</label>
                                        <select class="form-select item-selector" name="items[{{ $index }}][item_id]" 
                                                onchange="updateItemDetails(this)" {{ $detail->item_type != 'other' ? 'required' : '' }}>
                                            <option value="">Pilih...</option>
                                            @if($detail->item_type == 'medicine')
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}" 
                                                            data-name="{{ $medicine->nama_obat }}"
                                                            data-price="{{ $medicine->harga }}"
                                                            data-stock="{{ $medicine->stok }}"
                                                            {{ $detail->item_id == $medicine->id ? 'selected' : '' }}>
                                                        {{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }})
                                                    </option>
                                                @endforeach
                                            @elseif($detail->item_type == 'service')
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" 
                                                            data-name="{{ $service->nama_layanan }}"
                                                            data-price="{{ $service->tarif }}"
                                                            {{ $detail->item_id == $service->id ? 'selected' : '' }}>
                                                        {{ $service->nama_layanan }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>

                                    <!-- Nama Item (for 'other' type) -->
                                    <div class="col-md-3 mb-2 item-name-field" style="{{ $detail->item_type == 'other' ? '' : 'display: none;' }}">
                                        <label class="form-label">Nama Item</label>
                                        <input type="text" class="form-control item-name" 
                                               name="items[{{ $index }}][name]" 
                                               value="{{ $detail->item_name }}"
                                               placeholder="Masukkan nama item..." 
                                               {{ $detail->item_type == 'other' ? 'required' : '' }}>
                                    </div>

                                    <!-- Jumlah -->
                                    <div class="col-md-1 mb-2">
                                        <label class="form-label">Jumlah</label>
                                        <input type="number" class="form-control quantity" 
                                               name="items[{{ $index }}][quantity]" 
                                               value="{{ $detail->quantity }}" min="1" 
                                               onchange="calculateSubtotal(this)" required>
                                    </div>

                                    <!-- Harga -->
                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">Harga (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control price" 
                                                   name="items[{{ $index }}][price]" 
                                                   value="{{ $detail->price }}" min="0" step="100"
                                                   onchange="calculateSubtotal(this)" required>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-md-2 mb-2">
                                        <label class="form-label">Subtotal</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control subtotal" 
                                                   value="{{ number_format($detail->subtotal, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="col-md-1 mb-2 d-flex align-items-end">
                                        @if($loop->first)
                                        <button type="button" class="btn btn-danger btn-sm w-100" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeItem(this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>

                                    <!-- Catatan -->
                                    <div class="col-md-10 mb-2">
                                        <label class="form-label">Catatan (Opsional)</label>
                                        <input type="text" class="form-control" 
                                               name="items[{{ $index }}][note]" 
                                               value="{{ $detail->note }}"
                                               placeholder="Contoh: Aturan pakai...">
                                    </div>

                                    <!-- Hidden original values for medicine stock adjustment -->
                                    @if($detail->item_type == 'medicine' && $detail->item_id)
                                    <input type="hidden" name="items[{{ $index }}][original_quantity]" value="{{ $detail->quantity }}">
                                    <input type="hidden" name="items[{{ $index }}][original_item_id]" value="{{ $detail->item_id }}">
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Empty State -->
                        <div id="emptyItems" class="text-center p-5" style="{{ $transaction->details->count() > 0 ? 'display: none;' : '' }}">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada item transaksi.</p>
                            <button type="button" class="btn btn-primary" onclick="addItem()">
                                <i class="fas fa-plus me-2"></i> Tambah Item Pertama
                            </button>
                        </div>

                        <!-- Quick Add Buttons -->
                        <div class="mt-3">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-success" onclick="addMedicineItem()">
                                    <i class="fas fa-pills"></i> Tambah Obat
                                </button>
                                <button type="button" class="btn btn-outline-info" onclick="addServiceItem()">
                                    <i class="fas fa-hand-holding-medical"></i> Tambah Layanan
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="addOtherItem()">
                                    <i class="fas fa-plus"></i> Tambah Lainnya
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i> Reset Perubahan
                                </button>
                            </div>
                            <div class="btn-group">
                                <button type="submit" name="action" value="save" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                                </button>
                                <button type="submit" name="action" value="save_print" class="btn btn-success">
                                    <i class="fas fa-print me-2"></i> Simpan & Cetak Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('styles')
<style>
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 12px;
        text-transform: uppercase;
    }
    
    .status-lunas {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-menunggu {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-batal {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .item-row {
        background-color: #f8f9fa;
        transition: all 0.3s;
        position: relative;
    }
    
    .item-row:hover {
        background-color: #e9ecef;
    }
    
    .item-row::before {
        content: attr(data-index);
        position: absolute;
        top: 10px;
        left: -25px;
        width: 20px;
        height: 20px;
        background-color: #6c757d;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }
    
    #emptyItems {
        background-color: #f8f9fa;
        border-radius: 5px;
        border: 2px dashed #dee2e6;
    }
    
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        margin-bottom: 0;
    }
    
    .form-label {
        font-weight: 500;
    }
    
    .alert {
        border-left: 4px solid;
    }
    
    .alert-warning {
        border-left-color: #ffc107;
    }
    
    .alert-info {
        border-left-color: #0dcaf0;
    }
</style>
@endsection

@section('scripts')
<script>
    let itemCounter = {{ $transaction->details->count() }};
    let totalAmount = {{ $transaction->details->sum('subtotal') }};
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize calculations
        calculateAll();
        calculateChange();
        toggleProofSection();
        
        // Initialize all item fields
        document.querySelectorAll('.item-type').forEach(select => {
            updateItemFields(select);
        });
        
        // Preview new proof
        const proofInput = document.getElementById('bukti_pembayaran');
        if (proofInput) {
            proofInput.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const preview = document.getElementById('proofPreview');
                    
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `
                                <div class="border p-2 rounded">
                                    <img src="${e.target.result}" class="img-fluid" style="max-height: 100px;">
                                    <small class="d-block mt-1">${file.name}</small>
                                </div>
                            `;
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = `
                            <div class="alert alert-info p-2">
                                <i class="fas fa-file-pdf"></i> ${file.name}
                            </div>
                        `;
                    }
                }
            });
        }
    });
    
    function toggleProofSection() {
        const method = document.getElementById('metode_pembayaran');
        if (!method) return;
        
        const methodValue = method.value;
        const proofSection = document.getElementById('proofSection');
        
        if (methodValue === 'tunai') {
            proofSection.style.display = 'none';
            document.getElementById('bukti_pembayaran').required = false;
        } else {
            proofSection.style.display = 'block';
            document.getElementById('bukti_pembayaran').required = true;
        }
    }
    
    function calculateChange() {
        const amountPaid = parseFloat(document.getElementById('jumlah_dibayar').value) || 0;
        const change = amountPaid - totalAmount;
        
        const changeElement = document.getElementById('changeAmount');
        const messageElement = document.getElementById('changeMessage');
        
        changeElement.textContent = 'Rp ' + Math.max(0, change).toLocaleString('id-ID');
        
        if (amountPaid === 0) {
            changeElement.className = 'fs-4 text-muted';
            messageElement.innerHTML = '<span class="text-info"><i class="fas fa-info-circle"></i> Masukkan jumlah pembayaran</span>';
        } else if (change < 0) {
            changeElement.className = 'fs-4 text-danger';
            messageElement.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Kurang bayar: Rp ' + Math.abs(change).toLocaleString('id-ID') + '</span>';
        } else if (change === 0) {
            changeElement.className = 'fs-4 text-success';
            messageElement.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Pembayaran pas</span>';
        } else {
            changeElement.className = 'fs-4 text-success';
            messageElement.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Ada kembalian</span>';
        }
        
        // Validate if status is "lunas"
        checkPaidAmount();
    }
    
    function checkPaidAmount() {
        const status = document.getElementById('status').value;
        const amountPaid = parseFloat(document.getElementById('jumlah_dibayar').value) || 0;
        
        if (status === 'lunas' && amountPaid < totalAmount) {
            document.getElementById('changeMessage').innerHTML = 
                '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Status "Lunas" tidak bisa dipilih jika jumlah bayar kurang dari total!</span>';
            document.getElementById('status').classList.add('is-invalid');
        } else {
            document.getElementById('status').classList.remove('is-invalid');
        }
    }
    
    function updateItemFields(select) {
        const itemRow = select.closest('.item-row');
        const itemType = select.value;
        const itemSelect = itemRow.querySelector('.item-selector');
        const itemNameField = itemRow.querySelector('.item-name-field');
        const itemSelectContainer = itemRow.querySelector('.item-select');
        
        // Clear and update options based on type
        if (itemType === 'medicine') {
            itemSelect.innerHTML = `
                <option value="">Pilih obat...</option>
                @foreach($medicines as $medicine)
                <option value="{{ $medicine->id }}" 
                        data-name="{{ $medicine->nama_obat }}"
                        data-price="{{ $medicine->harga }}"
                        data-stock="{{ $medicine->stok }}">
                    {{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }})
                </option>
                @endforeach
            `;
            itemSelectContainer.style.display = 'block';
            itemNameField.style.display = 'none';
            itemRow.querySelector('.item-selector').required = true;
            itemRow.querySelector('.item-name').required = false;
        } else if (itemType === 'service') {
            itemSelect.innerHTML = `
                <option value="">Pilih layanan...</option>
                @foreach($services as $service)
                <option value="{{ $service->id }}" 
                        data-name="{{ $service->nama_layanan }}"
                        data-price="{{ $service->tarif }}">
                    {{ $service->nama_layanan }}
                </option>
                @endforeach
            `;
            itemSelectContainer.style.display = 'block';
            itemNameField.style.display = 'none';
            itemRow.querySelector('.item-selector').required = true;
            itemRow.querySelector('.item-name').required = false;
        } else {
            itemSelectContainer.style.display = 'none';
            itemNameField.style.display = 'block';
            itemRow.querySelector('.item-name').value = '';
            itemRow.querySelector('.item-name').placeholder = 'Masukkan nama item...';
            itemRow.querySelector('.item-selector').required = false;
            itemRow.querySelector('.item-name').required = true;
        }
        
        calculateSubtotal(itemRow.querySelector('.quantity'));
    }
    
    function updateItemDetails(select) {
        const selectedOption = select.options[select.selectedIndex];
        const itemRow = select.closest('.item-row');
        
        if (selectedOption.value) {
            const itemName = selectedOption.getAttribute('data-name');
            const itemPrice = selectedOption.getAttribute('data-price');
            const stock = selectedOption.getAttribute('data-stock');
            
            itemRow.querySelector('.item-name').value = itemName;
            itemRow.querySelector('.price').value = itemPrice;
            
            // Show stock warning for medicines
            if (stock) {
                const quantityInput = itemRow.querySelector('.quantity');
                quantityInput.max = stock;
                
                if (parseInt(stock) <= 0) {
                    alert('Stok obat habis!');
                    quantityInput.value = 0;
                }
            }
            
            calculateSubtotal(itemRow.querySelector('.quantity'));
        }
    }
    
    function calculateSubtotal(input) {
        const itemRow = input.closest('.item-row');
        const quantity = parseFloat(itemRow.querySelector('.quantity').value) || 0;
        const price = parseFloat(itemRow.querySelector('.price').value) || 0;
        const subtotal = quantity * price;
        
        itemRow.querySelector('.subtotal').value = subtotal.toLocaleString('id-ID');
        calculateAll();
    }
    
    function calculateAll() {
        let totalSubtotal = 0;
        let totalItems = 0;
        
        document.querySelectorAll('.item-row').forEach(row => {
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const subtotal = quantity * price;
            
            totalSubtotal += subtotal;
            totalItems += quantity;
        });
        
        totalAmount = totalSubtotal;
        
        document.getElementById('totalItems').textContent = document.querySelectorAll('.item-row').length + ' item';
        document.getElementById('totalAmount').textContent = 'Rp ' + totalSubtotal.toLocaleString('id-ID');
        
        calculateChange();
    }
    
    function addItem(type = 'medicine') {
        const container = document.getElementById('itemsContainer');
        const newItem = document.createElement('div');
        newItem.className = 'item-row mb-3 p-3 border rounded bg-light';
        newItem.setAttribute('data-index', itemCounter);
        newItem.innerHTML = `
            <div class="row">
                <input type="hidden" name="items[${itemCounter}][id]" value="">
                
                <div class="col-md-2 mb-2">
                    <label class="form-label">Jenis Item</label>
                    <select class="form-select item-type" name="items[${itemCounter}][type]" onchange="updateItemFields(this)" required>
                        <option value="medicine" ${type === 'medicine' ? 'selected' : ''}>Obat</option>
                        <option value="service" ${type === 'service' ? 'selected' : ''}>Layanan</option>
                        <option value="other" ${type === 'other' ? 'selected' : ''}>Lainnya</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-2 item-select">
                    <label class="form-label">Pilih Item</label>
                    <select class="form-select item-selector" name="items[${itemCounter}][item_id]" onchange="updateItemDetails(this)" required>
                        <option value="">Pilih...</option>
                        ${type === 'medicine' ? `
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}" 
                                        data-name="{{ $medicine->nama_obat }}"
                                        data-price="{{ $medicine->harga }}"
                                        data-stock="{{ $medicine->stok }}">
                                    {{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }})
                                </option>
                            @endforeach
                        ` : ''}
                        ${type === 'service' ? `
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" 
                                        data-name="{{ $service->nama_layanan }}"
                                        data-price="{{ $service->tarif }}">
                                    {{ $service->nama_layanan }}
                                </option>
                            @endforeach
                        ` : ''}
                    </select>
                </div>
                
                <div class="col-md-3 mb-2 item-name-field" style="display: none;">
                    <label class="form-label">Nama Item</label>
                    <input type="text" class="form-control item-name" 
                           name="items[${itemCounter}][name]" 
                           placeholder="Masukkan nama item...">
                </div>
                
                <div class="col-md-1 mb-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control quantity" 
                           name="items[${itemCounter}][quantity]" value="1" min="1" 
                           onchange="calculateSubtotal(this)" required>
                </div>
                
                <div class="col-md-2 mb-2">
                    <label class="form-label">Harga (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control price" 
                               name="items[${itemCounter}][price]" value="0" min="0" step="100"
                               onchange="calculateSubtotal(this)" required>
                    </div>
                </div>
                
                <div class="col-md-2 mb-2">
                    <label class="form-label">Subtotal</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control subtotal" value="0" readonly>
                    </div>
                </div>
                
                <div class="col-md-1 mb-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeItem(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                
                <div class="col-md-10 mb-2">
                    <label class="form-label">Catatan (Opsional)</label>
                    <input type="text" class="form-control" 
                           name="items[${itemCounter}][note]" 
                           placeholder="Contoh: Aturan pakai...">
                </div>
            </div>
        `;
        
        container.appendChild(newItem);
        updateItemFields(newItem.querySelector('.item-type'));
        itemCounter++;
        
        // Hide empty state
        document.getElementById('emptyItems').style.display = 'none';
        
        // Update item numbers
        updateItemNumbers();
    }
    
    function addMedicineItem() {
        addItem('medicine');
    }
    
    function addServiceItem() {
        addItem('service');
    }
    
    function addOtherItem() {
        addItem('other');
    }
    
    function removeItem(button) {
        const item = button.closest('.item-row');
        
        if (document.querySelectorAll('.item-row').length > 1) {
            if (confirm('Hapus item ini dari transaksi?')) {
                item.remove();
                calculateAll();
                updateItemNumbers();
                
                // Show empty state if no items
                if (document.querySelectorAll('.item-row').length === 0) {
                    document.getElementById('emptyItems').style.display = 'block';
                }
            }
        } else {
            alert('Minimal satu item harus ada dalam transaksi.');
        }
    }
    
    function updateItemNumbers() {
        document.querySelectorAll('.item-row').forEach((row, index) => {
            row.setAttribute('data-index', index);
            // Update all input names
            row.querySelectorAll('[name^="items["]').forEach(input => {
                const oldName = input.getAttribute('name');
                const newName = oldName.replace(/items\[\d+\]/, `items[${index}]`);
                input.setAttribute('name', newName);
            });
        });
        
        document.getElementById('totalItems').textContent = document.querySelectorAll('.item-row').length + ' item';
    }
    
    function resetForm() {
        if (confirm('Reset semua perubahan? Semua data akan dikembalikan ke nilai awal.')) {
            location.reload();
        }
    }
    
    // Form validation
    document.getElementById('editForm').addEventListener('submit', function(e) {
        // Validate items
        let valid = true;
        let errorMessages = [];
        
        document.querySelectorAll('.item-row').forEach((row, index) => {
            const itemType = row.querySelector('.item-type').value;
            const itemName = row.querySelector('.item-name').value.trim();
            const itemSelect = row.querySelector('.item-selector');
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            
            if (itemType === 'other' && !itemName) {
                valid = false;
                errorMessages.push(`Item #${index + 1}: Nama item harus diisi untuk jenis "Lainnya".`);
                row.querySelector('.item-name').focus();
            }
            
            if (itemType !== 'other' && (!itemSelect.value || itemSelect.value === '')) {
                valid = false;
                errorMessages.push(`Item #${index + 1}: Pilih item dari daftar.`);
                itemSelect.focus();
            }
            
            if (price <= 0) {
                valid = false;
                errorMessages.push(`Item #${index + 1}: Harga harus lebih dari 0.`);
                row.querySelector('.price').focus();
            }
            
            if (quantity <= 0) {
                valid = false;
                errorMessages.push(`Item #${index + 1}: Jumlah harus lebih dari 0.`);
                row.querySelector('.quantity').focus();
            }
            
            // Check medicine stock
            if (itemType === 'medicine' && itemSelect.value) {
                const stock = parseInt(itemSelect.options[itemSelect.selectedIndex].getAttribute('data-stock')) || 0;
                if (quantity > stock) {
                    valid = false;
                    errorMessages.push(`Item #${index + 1}: Stok tidak cukup (tersedia: ${stock}).`);
                }
            }
        });
        
        // Check if any items
        if (document.querySelectorAll('.item-row').length === 0) {
            valid = false;
            errorMessages.push('Tambahkan minimal satu item ke transaksi.');
        }
        
        // Check payment amount for "lunas" status
        const status = document.getElementById('status').value;
        const amountPaid = parseFloat(document.getElementById('jumlah_dibayar').value) || 0;
        
        if (status === 'lunas' && amountPaid < totalAmount) {
            valid = false;
            errorMessages.push('Status "Lunas" tidak bisa dipilih jika jumlah bayar kurang dari total.');
        }
        
        if (!valid) {
            e.preventDefault();
            alert('Perbaiki kesalahan berikut:\n\n' + errorMessages.join('\n'));
            return false;
        }
        
        // Show loading
        const submitBtns = this.querySelectorAll('button[type="submit"]');
        submitBtns.forEach(btn => {
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
            btn.disabled = true;
        });
        
        return true;
    });
</script>
@endsection