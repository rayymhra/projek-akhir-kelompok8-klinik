@extends('layouts.app')

@section('title', 'Tambah Layanan Baru')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-plus-circle text-success"></i> Tambah Layanan Baru</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> --}}
                    <li class="breadcrumb-item"><a href="{{ route('services.index') }}">Layanan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('services.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Form Tambah Layanan</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('services.store') }}" id="serviceForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Nama Layanan -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_layanan" class="form-label fw-bold">Nama Layanan *</label>
                                <input type="text" class="form-control @error('nama_layanan') is-invalid @enderror" 
                                       id="nama_layanan" name="nama_layanan" 
                                       value="{{ old('nama_layanan') }}" 
                                       placeholder="Contoh: Konsultasi Dokter Umum" required>
                                @error('nama_layanan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Nama lengkap layanan yang akan ditampilkan</div>
                            </div>

                            <!-- Kode Layanan -->
                            <div class="col-md-6 mb-3">
                                <label for="kode_layanan" class="form-label">Kode Layanan</label>
                                <input type="text" class="form-control @error('kode_layanan') is-invalid @enderror" 
                                       id="kode_layanan" name="kode_layanan" 
                                       value="{{ old('kode_layanan') }}" 
                                       placeholder="Contoh: KONSULTASI-UMUM">
                                @error('kode_layanan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Biarkan kosong untuk generate otomatis
                                </div>
                            </div>

                            <!-- Tarif -->
                            <div class="col-md-6 mb-3">
                                <label for="tarif" class="form-label fw-bold">Tarif (Rp) *</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('tarif') is-invalid @enderror" 
                                           id="tarif" name="tarif" 
                                           value="{{ old('tarif') }}" 
                                           min="0" step="1000" required>
                                </div>
                                @error('tarif')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Tarif standar untuk layanan ini</div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-md-12 mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" name="deskripsi" 
                                          rows="3" 
                                          placeholder="Deskripsi singkat tentang layanan ini...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Opsional: Penjelasan detail tentang layanan</div>
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="card bg-light mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-eye me-2"></i> Preview</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Kode:</strong></p>
                                        <p id="previewKode" class="text-muted">Akan digenerate otomatis</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Nama:</strong></p>
                                        <p id="previewNama" class="text-muted">-</p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1"><strong>Tarif:</strong></p>
                                        <p id="previewTarif" class="text-muted">Rp 0</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i> Reset Form
                            </button>
                            <div class="btn-group">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i> Simpan Layanan
                                </button>
                                <button type="submit" name="add_another" value="1" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i> Simpan & Tambah Lagi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-lightbulb text-warning me-2"></i> Tips Pengisian</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li><strong>Nama Layanan:</strong> Gunakan nama yang jelas dan mudah dipahami pasien</li>
                        <li><strong>Kode:</strong> Gunakan format singkat tanpa spasi, contoh: "KONSULTASI-UMUM"</li>
                        <li><strong>Tarif:</strong> Sesuaikan dengan kebijakan harga klinik</li>
                        <li><strong>Deskripsi:</strong> Tambahkan jika ada informasi khusus yang perlu diketahui</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preview update
        function updatePreview() {
            // Nama
            const nama = document.getElementById('nama_layanan').value || '-';
            document.getElementById('previewNama').textContent = nama;
            
            // Kode
            const kode = document.getElementById('kode_layanan').value;
            if (kode) {
                document.getElementById('previewKode').textContent = kode;
            } else {
                document.getElementById('previewKode').textContent = 'Akan digenerate otomatis';
            }
            
            // Tarif
            const tarif = parseFloat(document.getElementById('tarif').value) || 0;
            document.getElementById('previewTarif').textContent = 
                'Rp ' + tarif.toLocaleString('id-ID');
        }
        
        // Update preview on input
        document.getElementById('nama_layanan').addEventListener('input', updatePreview);
        document.getElementById('kode_layanan').addEventListener('input', updatePreview);
        document.getElementById('tarif').addEventListener('input', updatePreview);
        document.getElementById('deskripsi').addEventListener('input', updatePreview);
        
        // Initialize preview
        updatePreview();
        
        // Form validation
        document.getElementById('serviceForm').addEventListener('submit', function(e) {
            const tarif = parseFloat(document.getElementById('tarif').value) || 0;
            
            if (tarif < 0) {
                e.preventDefault();
                alert('Tarif tidak boleh negatif');
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
        
        // Auto-generate kode from nama
        document.getElementById('nama_layanan').addEventListener('blur', function() {
            const kodeInput = document.getElementById('kode_layanan');
            const nama = this.value.trim();
            
            // Only auto-generate if kode is empty
            if (nama && !kodeInput.value) {
                // Simple conversion: remove special chars, uppercase, replace spaces with dash
                let kode = nama.toUpperCase()
                    .replace(/[^A-Z0-9 ]/g, '') // Remove special chars
                    .trim()
                    .replace(/\s+/g, '-'); // Replace spaces with dash
                
                kodeInput.value = kode;
                updatePreview();
            }
        });
    });
</script>
@endsection

@section('styles')
<style>
    .form-label {
        font-weight: 500;
    }
    
    .form-text {
        font-size: 0.85rem;
    }
    
    .card-header {
        font-weight: 600;
    }
    
    #previewKode, #previewNama, #previewTarif {
        font-weight: 500;
        color: #495057;
    }
</style>
@endsection