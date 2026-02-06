@extends('layouts.app')

@section('title', 'Manajemen Obat')

@section('styles')
<style>
    /* Stock level indicators */
    .stock-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 12px;
    }
    
    .stock-danger {
        background-color: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    .stock-warning {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    
    .stock-success {
        background-color: rgba(34, 197, 94, 0.1);
        color: #16a34a;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }
    
    /* Expiry status */
    .expiry-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .expired {
        background-color: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    .expiring-soon {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }
    
    .expiry-safe {
        background-color: rgba(34, 197, 94, 0.1);
        color: #16a34a;
    }
    
    /* Medicine card hover effects */
    .medicine-card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    
    .medicine-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .medicine-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 4px;
    }
    
    .medicine-card[data-type="Tablet"]::before { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .medicine-card[data-type="Sirup"]::before { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .medicine-card[data-type="Kapsul"]::before { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .medicine-card[data-type="Salep"]::before { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .medicine-card[data-type="Injeksi"]::before { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
    
    /* Quick action buttons */
    .quick-action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .quick-action-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    /* Medicine type tags */
    .medicine-type-tag {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Search and filter improvements */
    .search-container {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        z-index: 10;
    }
    
    .search-input {
        padding-left: 40px !important;
    }
    
    /* Empty state */
    .empty-medicines {
        padding: 60px 20px;
        text-align: center;
        color: var(--text-secondary);
    }
    
    .empty-medicines i {
        font-size: 3rem;
        margin-bottom: 16px;
        opacity: 0.3;
    }
    
    /* Price display */
    .price-display {
        font-family: 'Inter', monospace;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    /* Stock progress bar */
    .stock-progress {
        height: 6px;
        border-radius: 3px;
        background-color: var(--border-color);
        overflow: hidden;
        margin-top: 4px;
    }
    
    .stock-progress-bar {
        height: 100%;
        border-radius: 3px;
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        .stats-row {
            gap: 12px;
        }
        
        .medicine-type-tag {
            font-size: 10px;
            padding: 3px 8px;
        }
        
        .quick-action-btn {
            width: 32px;
            height: 32px;
        }
    }
    
    /* Medicine code styling */
    .medicine-code {
        font-family: 'Inter', monospace;
        font-weight: 600;
        color: var(--primary-color);
        background: rgba(99, 102, 241, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
    }
    
    /* Alert warnings */
    .expiry-alert {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-1">Manajemen Obat</h1>
                <p class="page-subtitle">Kelola inventaris obat dan stok di klinik</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('medicines.create') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Obat
                </a>
                <div class="btn-group">
                    <a href="{{ route('medicines.low-stock') }}" class="btn btn-warning px-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>Stok Rendah
                    </a>
                    <a href="{{ route('medicines.expired-soon') }}" class="btn btn-danger px-3">
                        <i class="fas fa-calendar-times me-2"></i>Kedaluwarsa
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card filter-card mb-4">
            <div class="card-body p-3">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Jenis Obat</label>
                        <select name="jenis" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Jenis</option>
                            @foreach($medicineTypes as $type)
                                <option value="{{ $type }}" {{ request('jenis') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Status Stok</label>
                        <select name="stock" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">Semua Stok</option>
                            <option value="low" {{ request('stock') == 'low' ? 'selected' : '' }}>Stok Rendah (≤10)</option>
                            <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted mb-1">Cari Obat</label>
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control form-control-sm search-input" 
                                   name="search" placeholder="Nama atau kode obat..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="d-flex gap-2 w-100">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            @if(request()->anyFilled(['jenis', 'stock', 'search']))
                            <a href="{{ route('medicines.index') }}" class="btn btn-outline-danger btn-sm" type="button">
                                <i class="fas fa-times"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4 stats-row">
            <div class="col-md-3">
                <div class="card medicine-card" data-type="Tablet">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 fw-semibold">JENIS OBAT</h6>
                                <h2 class="mb-0">{{ $medicines->total() }}</h2>
                                <small class="text-muted">Jenis tersedia</small>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-pills fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card medicine-card" data-type="Kapsul">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 fw-semibold">TOTAL STOK</h6>
                                <h2 class="mb-0">{{ number_format($totalStock) }}</h2>
                                <small class="text-muted">Unit tersedia</small>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-boxes fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card medicine-card" data-type="Sirup">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 fw-semibold">NILAI STOK</h6>
                                <h2 class="mb-0">Rp {{ number_format($totalValue, 0, ',', '.') }}</h2>
                                <small class="text-muted">Total nilai</small>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-money-bill-wave fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card medicine-card {{ $lowStockCount > 0 ? 'border-warning' : '' }}">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1 fw-semibold">STOK RENDAH</h6>
                                <h2 class="mb-0 {{ $lowStockCount > 0 ? 'text-warning' : '' }}">{{ $lowStockCount }}</h2>
                                <small class="text-muted">Perlu perhatian</small>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 fw-semibold">
                    <i class="fas fa-capsules me-2"></i> Daftar Obat
                </h5>
            </div>
            <div class="card-body p-0">
                @if($medicines->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th width="120">Kode</th>
                                <th>Nama Obat</th>
                                <th width="100">Jenis</th>
                                <th width="120">Stok</th>
                                <th width="120">Harga</th>
                                <th width="120">Kadaluwarsa</th>
                                <th width="100">Status</th>
                                <th width="140" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($medicines as $medicine)
                            @php
                                $expiryStatus = 'safe';
                                $expiryText = '';
                                if($medicine->expired_date) {
                                    if($medicine->expired_date->isPast()) {
                                        $expiryStatus = 'expired';
                                        $expiryText = 'Kedaluwarsa';
                                    } elseif($medicine->expired_date->diffInDays(now()) <= 30) {
                                        $expiryStatus = 'expiring-soon';
                                        $expiryText = 'Segera';
                                    }
                                }
                            @endphp
                            <tr>
                                <td class="text-muted">
                                    {{ $loop->iteration + (($medicines->currentPage() - 1) * $medicines->perPage()) }}
                                </td>
                                <td>
                                    <span class="medicine-code">
                                        {{ $medicine->kode_obat }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="mb-1">{{ $medicine->nama_obat }}</strong>
                                        @if($medicine->keterangan)
                                        <small class="text-muted">{{ Str::limit($medicine->keterangan, 50) }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="medicine-type-tag bg-light text-dark">
                                        {{ $medicine->jenis_obat }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="stock-indicator 
                                            @if($medicine->stok == 0) stock-danger
                                            @elseif($medicine->stok <= 10) stock-warning
                                            @else stock-success @endif">
                                            <i class="fas fa-box fa-xs"></i>
                                            {{ $medicine->stok }}
                                        </span>
                                        <div class="stock-progress mt-2">
                                            @php
                                                $stockPercentage = min(100, ($medicine->stok / 50) * 100);
                                                $barColor = $medicine->stok == 0 ? '#ef4444' : ($medicine->stok <= 10 ? '#f59e0b' : '#10b981');
                                            @endphp
                                            <div class="stock-progress-bar" 
                                                 style="width: {{ $stockPercentage }}%; background-color: {{ $barColor }};">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="price-display">
                                        Rp {{ number_format($medicine->harga, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @if($medicine->expired_date)
                                    <div class="d-flex flex-column gap-1">
                                        <span class="{{ $expiryStatus }}">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $medicine->expired_date->format('d/m/Y') }}
                                        </span>
                                        @if($expiryStatus != 'safe')
                                        <small class="expiry-badge {{ $expiryStatus }}">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $expiryText }}
                                        </small>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($medicine->stok == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($medicine->stok <= 10)
                                        <span class="badge bg-warning">Rendah</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Quick Actions -->
                                        <a href="{{ route('medicines.stock-history', $medicine) }}" 
                                           class="quick-action-btn btn btn-sm btn-light"
                                           data-bs-toggle="tooltip" title="Riwayat Stok">
                                            <i class="fas fa-history text-info"></i>
                                        </a>
                                        
                                        <a href="{{ route('medicines.edit', $medicine) }}" 
                                           class="quick-action-btn btn btn-sm btn-light"
                                           data-bs-toggle="tooltip" title="Edit Obat">
                                            <i class="fas fa-edit text-warning"></i>
                                        </a>
                                        
                                        <!-- Delete Trigger -->
                                        <button type="button" 
                                                class="quick-action-btn btn btn-sm btn-light"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $medicine->id }}"
                                                data-bs-toggle="tooltip" title="Hapus Obat">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $medicine->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus Obat</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="text-center mb-4">
                                                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle d-inline-block">
                                                            <i class="fas fa-trash fa-2x text-danger"></i>
                                                        </div>
                                                    </div>
                                                    
                                                    <p class="text-center mb-3">Apakah Anda yakin ingin menghapus obat ini?</p>
                                                    
                                                    <div class="card border mb-3">
                                                        <div class="card-body">
                                                            <h6 class="mb-2">{{ $medicine->nama_obat }}</h6>
                                                            <div class="d-flex gap-3">
                                                                <small class="text-muted">
                                                                    <i class="fas fa-barcode me-1"></i>
                                                                    {{ $medicine->kode_obat }}
                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-box me-1"></i>
                                                                    Stok: {{ $medicine->stok }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    @if($medicine->prescriptions()->count() > 0)
                                                    <div class="alert alert-danger">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        Obat ini telah digunakan dalam {{ $medicine->prescriptions()->count() }} resep.
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('medicines.destroy', $medicine) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="fas fa-trash me-2"></i>Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-medicines">
                    <i class="fas fa-pills"></i>
                    <h5 class="mt-3 mb-2">Tidak ada data obat</h5>
                    <p class="text-muted mb-4">Tidak ditemukan obat yang sesuai dengan filter yang dipilih.</p>
                    <a href="{{ route('medicines.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Obat
                    </a>
                </div>
                @endif
            </div>
            @if($medicines->count() > 0)
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $medicines->firstItem() ?? 0 }}-{{ $medicines->lastItem() ?? 0 }} dari {{ $medicines->total() }} obat
                    </div>
                    {{ $medicines->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
        
        // Add search input clear functionality
        const searchInput = document.querySelector('.search-input');
        const clearBtn = document.querySelector('.btn-outline-danger');
        
        if (searchInput && clearBtn) {
            searchInput.addEventListener('input', function() {
                if (this.value) {
                    clearBtn.style.display = 'block';
                }
            });
        }
        
        // Highlight expired medicines
        const expiredRows = document.querySelectorAll('.expired');
        expiredRows.forEach(row => {
            const parentRow = row.closest('tr');
            if (parentRow) {
                parentRow.classList.add('expiry-alert');
            }
        });
    });
    
    // Auto focus search input when clear filter is clicked
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-outline-danger')) {
            const searchInput = document.querySelector('.search-input');
            if (searchInput) {
                searchInput.focus();
            }
        }
    });
</script>
@endsection