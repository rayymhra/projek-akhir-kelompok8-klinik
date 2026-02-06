@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="fas fa-receipt text-primary"></i> Detail Transaksi</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li> --}}
                    <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}">Transaksi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group">
                <a href="{{ route('transactions.invoice', $transaction) }}" target="_blank" class="btn btn-success">
                    <i class="fas fa-print"></i> Cetak Invoice
                </a>
                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    {{-- <li>
                        <a class="dropdown-item" href="{{ route('transactions.edit', $transaction) }}">
                            <i class="fas fa-edit"></i> Edit Transaksi
                        </a>
                    </li> --}}
                    @if($transaction->status === 'menunggu')
                    <li>
                        <form action="{{ route('transactions.confirm', $transaction)
 }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-success">
                                <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                            </button>
                        </form>
                    </li>
                    @endif
                    @if($transaction->status !== 'batal')
                    <li>
                        <form action="{{ route('transactions.cancel', $transaction)
 }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Batalkan transaksi ini?')">
                                <i class="fas fa-times-circle"></i> Batalkan Transaksi
                            </button>
                        </form>
                    </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Hapus transaksi ini?')">
                                <i class="fas fa-trash"></i> Hapus Transaksi
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0">Transaksi #TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</h3>
                    <small class="text-muted">Dibuat: {{ $transaction->created_at->format('d/m/Y H:i') }}</small>
                </div>
                <div class="status-badge status-{{ $transaction->status }}">
                    <i class="fas {{ $transaction->status === 'lunas' ? 'fa-check-circle' : ($transaction->status === 'menunggu' ? 'fa-clock' : 'fa-times-circle') }}"></i>
                    {{ strtoupper($transaction->status) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Utama -->
    <div class="row">
        <!-- Informasi Pasien -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user-injured me-2"></i> Informasi Pasien</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <p class="fs-5 fw-bold">{{ $transaction->visit->patient->nama }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">No. Rekam Medis</label>
                            <p class="fs-5">
                                <span class="badge bg-secondary">{{ $transaction->visit->patient->no_rekam_medis }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Jenis Kelamin</label>
                            <p class="fs-5">
                                @if($transaction->visit->patient->jenis_kelamin == 'L')
                                    <i class="fas fa-mars text-primary me-2"></i> Laki-laki
                                @else
                                    <i class="fas fa-venus text-danger me-2"></i> Perempuan
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Usia</label>
                            <p class="fs-5">{{ $transaction->visit->patient->usia }} tahun</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted">Alamat</label>
                            <p class="fs-6">{{ $transaction->visit->patient->alamat ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">No. Telepon</label>
                            <p class="fs-6">{{ $transaction->visit->patient->no_telepon ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Tanggal Kunjungan</label>
                            <p class="fs-6">{{ $transaction->visit->tanggal_kunjungan->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Transaksi -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Informasi Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Tanggal Transaksi</label>
                            <p class="fs-5 fw-bold">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Dokter</label>
                            <p class="fs-5">
                                <i class="fas fa-user-md text-primary me-2"></i>
                                {{ $transaction->visit->doctor->name }}
                            </p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted">Metode Pembayaran</label>
                            <div class="payment-method">
                                <i class="fas {{ $transaction->metode_pembayaran == 'tunai' ? 'fa-money-bill-wave' : ($transaction->metode_pembayaran == 'transfer' ? 'fa-university' : ($transaction->metode_pembayaran == 'qris' ? 'fa-qrcode' : 'fa-wallet')) }} fa-2x text-primary me-3"></i>
                                <div>
                                    <p class="fs-4 fw-bold mb-0">{{ strtoupper($transaction->metode_pembayaran) }}</p>
                                    <small class="text-muted">
                                        @if($transaction->metode_pembayaran == 'tunai')
                                            Pembayaran Tunai
                                        @elseif($transaction->metode_pembayaran == 'transfer')
                                            Transfer Bank
                                        @elseif($transaction->metode_pembayaran == 'qris')
                                            QRIS
                                        @else
                                            E-Wallet
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bukti Pembayaran -->
                        @if($transaction->bukti_pembayaran)
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted">Bukti Pembayaran</label>
                            <div class="border rounded p-3">
                                @if(Str::endsWith($transaction->bukti_pembayaran, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ Storage::url($transaction->bukti_pembayaran) }}" 
                                         alt="Bukti Pembayaran" 
                                         class="img-fluid rounded" 
                                         style="max-height: 200px;">
                                @else
                                    <div class="text-center">
                                        <i class="fas fa-file-pdf fa-3x text-danger mb-2"></i>
                                        <p class="mb-0">File Bukti Pembayaran</p>
                                        <a href="{{ Storage::url($transaction->bukti_pembayaran) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Item Transaksi -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i> Detail Item Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Jenis</th>
                                    <th>Item / Layanan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-end">Subtotal</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($detail->item_type == 'medicine')
                                            <span class="badge bg-info">
                                                <i class="fas fa-pills"></i> Obat
                                            </span>
                                        @elseif($detail->item_type == 'service')
                                            <span class="badge bg-success">
                                                <i class="fas fa-hand-holding-medical"></i> Layanan
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-box"></i> Lainnya
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $detail->item_name }}</strong>
                                        @if($detail->item_type == 'medicine' && $detail->item)
                                            <br>
                                            <small class="text-muted">
                                                Stok tersisa: {{ $detail->item->stok ?? 'N/A' }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $detail->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $detail->note ?? '-' }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Jumlah Item:</strong></td>
                                    <td class="text-end" colspan="2">
                                        <span class="badge bg-secondary fs-6">
                                            {{ $transaction->details->sum('quantity') }} item
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>TOTAL BIAYA:</strong></td>
                                    <td colspan="2" class="text-end">
                                        <h4 class="text-success mb-0">
                                            Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}
                                        </h4>
                                    </td>
                                    <td></td>
                                </tr>
                                @if($transaction->jumlah_dibayar)
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Jumlah Dibayar:</strong></td>
                                    <td colspan="2" class="text-end">
                                        <h5 class="text-primary mb-0">
                                            Rp {{ number_format($transaction->jumlah_dibayar, 0, ',', '.') }}
                                        </h5>
                                    </td>
                                    <td></td>
                                </tr>
                                @endif
                                @if($transaction->kembalian > 0)
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Kembalian:</strong></td>
                                    <td colspan="2" class="text-end">
                                        <h5 class="text-danger mb-0">
                                            Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
                                        </h5>
                                    </td>
                                    <td></td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Tambahan & Aksi -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Informasi Tambahan</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Dibuat Oleh</label>
                            <p class="mb-0">
                                <i class="fas fa-user-circle text-primary me-2"></i>
                                {{ $transaction->createdBy->name ?? 'System' }}
                            </p>
                            <small class="text-muted">Pada: {{ $transaction->created_at->format('d/m/Y H:i:s') }}</small>
                        </div>
                        @if($transaction->updated_at != $transaction->created_at)
                        <div class="col-md-6">
                            <label class="form-label text-muted">Terakhir Diperbarui</label>
                            <p class="mb-0">
                                <i class="fas fa-edit text-warning me-2"></i>
                                {{ $transaction->updatedBy->name ?? 'System' }}
                            </p>
                            <small class="text-muted">Pada: {{ $transaction->updated_at->format('d/m/Y H:i:s') }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('transactions.invoice', $transaction) }}" target="_blank" class="btn btn-success">
                            <i class="fas fa-print me-2"></i> Cetak Invoice
                        </a>
                        
                        @if($transaction->status === 'menunggu')
                        <form action="{{ route('transactions.confirm', $transaction)
 }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Konfirmasi pembayaran ini?')">
                                <i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran
                            </button>
                        </form>
                        @endif
                        
                        @if($transaction->status !== 'batal')
                        <form action="{{ route('transactions.cancel', $transaction)
 }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Batalkan transaksi ini? Aksi ini tidak dapat dibatalkan.')">
                                <i class="fas fa-times-circle me-2"></i> Batalkan Transaksi
                            </button>
                        </form>
                        @endif
                        
                        <div class="btn-group" role="group">
                            {{-- <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a> --}}
                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Hapus transaksi ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Timeline Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Transaksi Dibuat</h6>
                                <p class="text-muted mb-0">{{ $transaction->created_at->format('d F Y H:i') }}</p>
                                <p class="mb-0">Status: 
                                    <span class="badge bg-warning">Menunggu Pembayaran</span>
                                </p>
                            </div>
                        </div>
                        
                        @if($transaction->status == 'lunas')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6>Pembayaran Dikonfirmasi</h6>
                                <p class="text-muted mb-0">{{ $transaction->updated_at->format('d F Y H:i') }}</p>
                                <p class="mb-0">Status: 
                                    <span class="badge bg-success">Lunas</span>
                                </p>
                                <p class="mb-0">Metode: {{ strtoupper($transaction->metode_pembayaran) }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($transaction->status == 'batal')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <h6>Transaksi Dibatalkan</h6>
                                <p class="text-muted mb-0">{{ $transaction->updated_at->format('d F Y H:i') }}</p>
                                <p class="mb-0">Status: 
                                    <span class="badge bg-danger">Batal</span>
                                </p>
                            </div>
                        </div>
                        @endif
                        
                        @if($transaction->bukti_pembayaran)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6>Bukti Pembayaran Diunggah</h6>
                                <p class="text-muted mb-0">
                                    <a href="{{ Storage::url($transaction->bukti_pembayaran) }}" target="_blank">
                                        <i class="fas fa-file-download"></i> Lihat Bukti
                                    </a>
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: bold;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .status-lunas {
        background-color: #d4edda;
        color: #155724;
        border: 2px solid #155724;
    }
    
    .status-menunggu {
        background-color: #fff3cd;
        color: #856404;
        border: 2px solid #856404;
    }
    
    .status-batal {
        background-color: #f8d7da;
        color: #721c24;
        border: 2px solid #721c24;
    }
    
    .payment-method {
        display: flex;
        align-items: center;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #0d6efd;
    }
    
    .table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background-color: #dee2e6;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #dee2e6;
    }
    
    .timeline-content {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        border-left: 3px solid #0d6efd;
    }
    
    .breadcrumb {
        background-color: transparent;
        padding: 0;
        margin-bottom: 0;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
    }
    
    .badge {
        font-size: 0.8em;
        padding: 5px 10px;
    }
</style>
@endsection

@section('scripts')
<script>
    // Confirm actions
    document.addEventListener('DOMContentLoaded', function() {
        // Confirm payment
        const confirmButtons = document.querySelectorAll('[data-confirm-payment]');
        confirmButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Konfirmasi pembayaran transaksi ini?')) {
                    e.preventDefault();
                }
            });
        });
        
        // Cancel transaction
        const cancelButtons = document.querySelectorAll('[data-cancel-transaction]');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Batalkan transaksi ini? Stok obat akan dikembalikan.')) {
                    e.preventDefault();
                }
            });
        });
        
        // Delete transaction
        const deleteButtons = document.querySelectorAll('[data-delete-transaction]');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Hapus transaksi ini? Aksi ini tidak dapat dibatalkan.')) {
                    e.preventDefault();
                }
            });
        });
        
        // Print invoice
        const printButton = document.querySelector('[data-print-invoice]');
        if (printButton) {
            printButton.addEventListener('click', function() {
                window.open(this.href, '_blank', 'width=800,height=600');
            });
        }
    });
    
    // Auto-refresh page every 30 seconds if status is "menunggu"
    @if($transaction->status === 'menunggu')
    setTimeout(function() {
        location.reload();
    }, 30000); // 30 seconds
    @endif
</script>
@endsection