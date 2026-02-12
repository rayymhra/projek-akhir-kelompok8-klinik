@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('styles')
<style>
    /* ========== MODERN STATUS BADGES ========== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        gap: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: none;
    }
    
    .status-lunas {
        background: linear-gradient(145deg, #d4edda, #c3e6cb);
        color: #155724;
        border: 1px solid #a3d0b0;
    }
    
    .status-menunggu {
        background: linear-gradient(145deg, #fff3cd, #ffe69c);
        color: #856404;
        border: 1px solid #ffe08c;
        animation: pulse-warning 2s infinite;
    }
    
    .status-batal {
        background: linear-gradient(145deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border: 1px solid #f1b0b7;
    }
    
    @keyframes pulse-warning {
        0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
        50% { opacity: 0.9; box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
    }
    
    /* ========== CUSTOM MODAL ========== */
    .custom-modal {
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(5px);
    }
    
    .modal-content-modern {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    .modal-header-modern {
        padding: 20px 24px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .modal-body-modern {
        padding: 24px;
    }
    
    .modal-footer-modern {
        padding: 20px 24px;
        border-top: 1px solid #e9ecef;
        background: #f8fafc;
    }
    
    .modal-icon-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .modal-icon-wrapper.warning {
        background: linear-gradient(145deg, #fff3cd, #ffe69c);
        color: #856404;
    }
    
    .modal-icon-wrapper.success {
        background: linear-gradient(145deg, #d4edda, #c3e6cb);
        color: #155724;
    }
    
    .modal-icon-wrapper.danger {
        background: linear-gradient(145deg, #f8d7da, #f5c6cb);
        color: #721c24;
    }
    
    .modal-icon-wrapper.info {
        background: linear-gradient(145deg, #d1ecf1, #bee5eb);
        color: #0c5460;
    }
    
    .modal-icon-wrapper i {
        font-size: 40px;
    }
    
    .modal-title-modern {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .modal-text {
        color: #64748b;
        font-size: 1rem;
        line-height: 1.6;
    }
    
    /* ========== UPLOAD AREA ========== */
    .upload-area {
        border: 2px dashed #cbd5e1;
        border-radius: 16px;
        padding: 32px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        margin-top: 16px;
    }
    
    .upload-area:hover {
        border-color: #0d6efd;
        background: #f1f5f9;
        transform: translateY(-2px);
    }
    
    .upload-area.dragover {
        border-color: #0d6efd;
        background: #e6f3ff;
        transform: scale(1.02);
    }
    
    .upload-icon {
        font-size: 48px;
        color: #94a3b8;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }
    
    .upload-area:hover .upload-icon {
        color: #0d6efd;
        transform: scale(1.1);
    }
    
    .upload-text {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }
    
    .upload-hint {
        color: #64748b;
        font-size: 0.85rem;
    }
    
    .file-preview {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-top: 16px;
        border: 1px solid #e2e8f0;
        animation: slideUp 0.3s ease;
    }
    
    .file-preview-item {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .file-preview-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
    }
    
    .file-preview-icon i {
        font-size: 24px;
    }
    
    .file-preview-info {
        flex: 1;
    }
    
    .file-preview-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .file-preview-size {
        color: #64748b;
        font-size: 0.85rem;
    }
    
    .file-preview-remove {
        color: #ef4444;
        cursor: pointer;
        padding: 8px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .file-preview-remove:hover {
        background: #fee2e2;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* ========== PAYMENT METHOD CARD ========== */
    .payment-method {
        display: flex;
        align-items: center;
        padding: 20px;
        background: linear-gradient(145deg, #f8fafc, #f1f5f9);
        border-radius: 16px;
        border-left: 6px solid #0d6efd;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .payment-method i {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border-radius: 12px;
        color: #0d6efd;
        font-size: 24px;
        margin-right: 16px;
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.1);
    }
    
    /* ========== CUSTOMER INFO CARD ========== */
    .patient-avatar {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(145deg, #0d6efd, #0b5ed7);
        color: white;
        font-size: 28px;
        font-weight: 700;
    }
    
    /* ========== TIMELINE MODERN ========== */
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 20px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: linear-gradient(to bottom, #0d6efd, #20c997, #dc3545);
        border-radius: 2px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -40px;
        top: 0;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .timeline-marker.bg-success { background: linear-gradient(145deg, #198754, #146c43); }
    .timeline-marker.bg-primary { background: linear-gradient(145deg, #0d6efd, #0b5ed7); }
    .timeline-marker.bg-danger { background: linear-gradient(145deg, #dc3545, #bb2d3b); }
    .timeline-marker.bg-info { background: linear-gradient(145deg, #0dcaf0, #31d2f2); }
    .timeline-marker.bg-warning { background: linear-gradient(145deg, #ffc107, #ffca2c); }
    
    .timeline-content {
        background: white;
        padding: 20px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .timeline-content:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    
    /* ========== USER INFO CARD ========== */
    .user-info-card {
        background: linear-gradient(145deg, #f8fafc, #f1f5f9);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    
    .user-info-card.created {
        border-left-color: #198754;
    }
    
    .user-info-card.updated {
        border-left-color: #ffc107;
    }
    
    .user-avatar {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .user-avatar i {
        font-size: 28px;
        color: #0d6efd;
    }
    
    .user-details {
        flex: 1;
    }
    
    .user-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .user-role {
        display: inline-block;
        padding: 4px 12px;
        background: #e6f3ff;
        color: #0d6efd;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* ========== ACTION BUTTONS ========== */
    .btn-modern {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    
    .btn-modern:active {
        transform: translateY(0);
    }
    
    .btn-primary-modern {
        background: linear-gradient(145deg, #0d6efd, #0b5ed7);
        color: white;
    }
    
    .btn-success-modern {
        background: linear-gradient(145deg, #198754, #146c43);
        color: white;
    }
    
    .btn-danger-modern {
        background: linear-gradient(145deg, #dc3545, #bb2d3b);
        color: white;
    }
    
    .btn-outline-modern {
        background: white;
        border: 2px solid #e9ecef;
        color: #1e293b;
    }
    
    .btn-outline-modern:hover {
        border-color: #0d6efd;
        color: #0d6efd;
        background: #f8fafc;
    }
    
    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .modal-icon-wrapper {
            width: 60px;
            height: 60px;
        }
        
        .modal-icon-wrapper i {
            font-size: 30px;
        }
        
        .modal-title-modern {
            font-size: 1.25rem;
        }
        
        .user-info-card {
            flex-direction: column;
            text-align: center;
        }
        
        .timeline {
            padding-left: 30px;
        }
        
        .timeline-marker {
            width: 35px;
            height: 35px;
            left: -35px;
            font-size: 14px;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Header Modern -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-lg rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="h2 mb-1 fw-bold text-primary">
                            <i class="fas fa-receipt me-2"></i> Detail Transaksi
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('transactions.index') }}" class="text-decoration-none">Transaksi</a></li>
                                <li class="breadcrumb-item active" aria-current="page">#TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="status-badge status-{{ $transaction->status }}">
                        <i class="fas {{ $transaction->status === 'lunas' ? 'fa-check-circle' : ($transaction->status === 'menunggu' ? 'fa-clock' : 'fa-times-circle') }}"></i>
                        {{ strtoupper($transaction->status) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Utama Modern -->
    <div class="row g-4">
        <!-- Informasi Pasien -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3" style="background: linear-gradient(145deg, #0d6efd, #0b5ed7);">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-user-injured me-2"></i> Informasi Pasien
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-start gap-4">
                        <div class="patient-avatar">
                            {{ strtoupper(substr($transaction->visit->patient->nama, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="fw-bold mb-2">{{ $transaction->visit->patient->nama }}</h3>
                            <div class="d-flex flex-wrap gap-3 mb-3">
                                <span class="badge bg-info text-white px-3 py-2">
                                    <i class="fas fa-id-card me-2"></i> {{ $transaction->visit->patient->no_rekam_medis }}
                                </span>
                                <span class="badge bg-secondary px-3 py-2">
                                    <i class="fas fa-calendar-alt me-2"></i> {{ $transaction->visit->patient->umur }} tahun
                                </span>
                                <span class="badge bg-{{ $transaction->visit->patient->jenis_kelamin == 'L' ? 'primary' : 'danger' }} px-3 py-2">
                                    <i class="fas fa-{{ $transaction->visit->patient->jenis_kelamin == 'L' ? 'mars' : 'venus' }} me-2"></i>
                                    {{ $transaction->visit->patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </span>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        <div>
                                            <small class="text-muted d-block">Alamat</small>
                                            <span class="fw-medium">{{ $transaction->visit->patient->alamat ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-phone-alt text-primary"></i>
                                        <div>
                                            <small class="text-muted d-block">No. Telepon</small>
                                            <span class="fw-medium">{{ $transaction->visit->patient->no_hp ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar-check text-primary"></i>
                                        <div>
                                            <small class="text-muted d-block">Tanggal Kunjungan</small>
                                            <span class="fw-medium">{{ $transaction->visit->tanggal_kunjungan->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-user-md text-primary"></i>
                                        <div>
                                            <small class="text-muted d-block">Dokter</small>
                                            <span class="fw-medium">{{ $transaction->visit->doctor->name }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Transaksi -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-lg">
                <div class="card-header bg-gradient-success text-white py-3" style="background: linear-gradient(145deg, #198754, #146c43);">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-file-invoice-dollar me-2"></i> Informasi Transaksi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="payment-method mb-4">
                        <i class="fas {{ $transaction->metode_pembayaran == 'tunai' ? 'fa-money-bill-wave' : ($transaction->metode_pembayaran == 'transfer' ? 'fa-university' : ($transaction->metode_pembayaran == 'qris' ? 'fa-qrcode' : 'fa-wallet')) }}"></i>
                        <div>
                            <p class="fs-3 fw-bold mb-0 text-dark">{{ strtoupper($transaction->metode_pembayaran) }}</p>
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
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-calendar-alt text-success"></i>
                                <div>
                                    <small class="text-muted d-block">Tanggal Transaksi</small>
                                    <span class="fs-5 fw-bold">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-tag text-success"></i>
                                <div>
                                    <small class="text-muted d-block">No. Transaksi</small>
                                    <span class="fs-6 fw-bold text-primary">#TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($transaction->status === 'menunggu')
                    <div class="alert alert-warning mt-3 mb-0">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-clock fa-2x"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Menunggu Konfirmasi Pembayaran</h6>
                                <p class="mb-0 small">Silakan unggah bukti pembayaran untuk mengkonfirmasi transaksi ini.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Item Transaksi -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-warning text-white py-3" style="background: linear-gradient(145deg, #ffc107, #ffca2c);">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-list-alt me-2"></i> Detail Item Transaksi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th width="100">Jenis</th>
                                    <th>Item / Layanan</th>
                                    <th width="100" class="text-center">Jumlah</th>
                                    <th width="150" class="text-end">Harga Satuan</th>
                                    <th width="150" class="text-end">Subtotal</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->details as $index => $detail)
                                <tr>
                                    <td class="fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        @if($detail->item_type == 'medicine')
                                            <span class="badge bg-info bg-opacity-25 text-dark px-3 py-2">
                                                <i class="fas fa-pills me-1"></i> Obat
                                            </span>
                                        @elseif($detail->item_type == 'service')
                                            <span class="badge bg-success bg-opacity-25 text-dark px-3 py-2">
                                                <i class="fas fa-hand-holding-medical me-1"></i> Layanan
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-25 text-dark px-3 py-2">
                                                <i class="fas fa-box me-1"></i> Lainnya
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $detail->item_name }}</strong>
                                        @if($detail->item_type == 'medicine' && $detail->item)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-box me-1"></i> Stok: {{ $detail->item->stok ?? 'N/A' }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill px-3 py-2">
                                            {{ $detail->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-end fw-medium">
                                        Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold text-primary">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $detail->note ?? '-' }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total Item:</strong></td>
                                    <td class="text-end">
                                        <span class="badge bg-secondary fs-6 px-4 py-2">
                                            {{ $transaction->details->sum('quantity') }} item
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-end"><h5 class="mb-0 fw-bold">TOTAL BIAYA:</h5></td>
                                    <td class="text-end">
                                        <h3 class="text-success fw-bold mb-0">
                                            Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}
                                        </h3>
                                    </td>
                                    <td></td>
                                </tr>
                                @if($transaction->jumlah_dibayar)
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Jumlah Dibayar:</strong></td>
                                    <td class="text-end">
                                        <h5 class="text-primary fw-bold mb-0">
                                            Rp {{ number_format($transaction->jumlah_dibayar, 0, ',', '.') }}
                                        </h5>
                                    </td>
                                    <td></td>
                                </tr>
                                @endif
                                @if($transaction->kembalian > 0)
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Kembalian:</strong></td>
                                    <td class="text-end">
                                        <h5 class="text-danger fw-bold mb-0">
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

    <!-- Bukti Pembayaran -->
    @if($transaction->bukti_pembayaran)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-info text-white py-3" style="background: linear-gradient(145deg, #0dcaf0, #31d2f2);">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-image me-2"></i> Bukti Pembayaran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center gap-4">
                                @if(Str::endsWith($transaction->bukti_pembayaran, ['.jpg', '.jpeg', '.png', '.gif']))
                                    <img src="{{ Storage::url($transaction->bukti_pembayaran) }}" 
                                         alt="Bukti Pembayaran" 
                                         class="img-fluid rounded" 
                                         style="max-height: 200px; box-shadow: 0 8px 24px rgba(0,0,0,0.1);">
                                @else
                                    <div class="text-center p-4 bg-light rounded">
                                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                        <p class="mb-2 fw-bold">File Bukti Pembayaran</p>
                                        <a href="{{ Storage::url($transaction->bukti_pembayaran) }}" 
                                           target="_blank" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-download me-2"></i> Download File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-4 rounded">
                                <h6 class="fw-bold mb-3">Informasi File:</h6>
                                <p class="mb-2">
                                    <i class="fas fa-file me-2"></i> 
                                    {{ basename($transaction->bukti_pembayaran) }}
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-clock me-2"></i>
                                    Diunggah: {{ $transaction->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Informasi User & Aksi -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <!-- Informasi Tambahan -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i> Informasi Tambahan
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Dibuat Oleh -->
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-user-plus text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Dibuat Oleh</small>
                                <h6 class="mb-1 fw-bold">
                                    {{ $transaction->createdBy->name ?? 'System' }}
                                </h6>
                                <small class="text-muted">
                                    {{ $transaction->created_at->format('d/m/Y H:i:s') }}
                                </small>
                                @if($transaction->createdBy)
                                    <span class="badge bg-light text-dark ms-2">
                                        {{ $transaction->createdBy->role }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Dikonfirmasi Oleh -->
                    @if($transaction->status === 'lunas')
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Dikonfirmasi Oleh</small>
                                <h6 class="mb-1 fw-bold">
                                    {{ $transaction->confirmedBy->name ?? 'System' }}
                                </h6>
                                <small class="text-muted">
                                    {{ $transaction->confirmed_at ? $transaction->confirmed_at->format('d/m/Y H:i:s') : '-' }}
                                </small>
                                @if($transaction->confirmedBy)
                                    <span class="badge bg-success text-white ms-2">
                                        {{ $transaction->confirmedBy->role }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Dibatalkan Oleh -->
                    @if($transaction->status === 'batal')
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-start">
                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Dibatalkan Oleh</small>
                                <h6 class="mb-1 fw-bold">
                                    {{ $transaction->cancelledBy->name ?? 'System' }}
                                </h6>
                                <small class="text-muted">
                                    {{ $transaction->cancelled_at ? $transaction->cancelled_at->format('d/m/Y H:i:s') : '-' }}
                                </small>
                                @if($transaction->cancelledBy)
                                    <span class="badge bg-danger text-white ms-2">
                                        {{ $transaction->cancelledBy->role }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-bolt me-2 text-warning"></i> Aksi Cepat
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-grid gap-3">
                        <a href="{{ route('transactions.invoice', $transaction) }}" target="_blank" 
                           class="btn btn-success-modern btn-modern">
                            <i class="fas fa-print"></i>
                            Cetak Invoice
                        </a>
                        
                        @if($transaction->status === 'menunggu')
                        <button type="button" class="btn btn-primary-modern btn-modern" 
                                data-bs-toggle="modal" 
                                data-bs-target="#confirmPaymentModal">
                            <i class="fas fa-check-circle"></i>
                            Konfirmasi Pembayaran
                        </button>
                        @endif
                        
                        @if($transaction->status !== 'batal')
                        <button type="button" class="btn btn-danger-modern btn-modern" 
                                data-bs-toggle="modal" 
                                data-bs-target="#cancelTransactionModal">
                            <i class="fas fa-times-circle"></i>
                            Batalkan Transaksi
                        </button>
                        @endif
                        
                        <button type="button" class="btn btn-outline-modern btn-modern" 
                                data-bs-toggle="modal" 
                                data-bs-target="#deleteTransactionModal">
                            <i class="fas fa-trash"></i>
                            Hapus Transaksi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Modern -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-history me-2 text-info"></i> Timeline Transaksi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="fw-bold mb-2">Transaksi Dibuat</h5>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-user me-2"></i> {{ $transaction->createdBy->name ?? 'System' }}
                                        </p>
                                        <p class="mb-0">
                                            <span class="badge bg-warning">Menunggu Pembayaran</span>
                                        </p>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $transaction->created_at->format('d F Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        @if($transaction->bukti_pembayaran)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info">
                                <i class="fas fa-image"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="fw-bold mb-2">Bukti Pembayaran Diunggah</h5>
                                        <p class="mb-2">
                                            <a href="{{ Storage::url($transaction->bukti_pembayaran) }}" target="_blank" class="text-decoration-none">
                                                <i class="fas fa-file-download me-2"></i> Lihat Bukti Pembayaran
                                            </a>
                                        </p>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $transaction->updated_at->format('d F Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($transaction->status == 'lunas')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="fw-bold mb-2">Pembayaran Dikonfirmasi</h5>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-user me-2"></i> {{ $transaction->confirmedBy->name ?? 'System' }}
                                        </p>
                                        <p class="mb-0">
                                            <span class="badge bg-success">Lunas</span>
                                            <span class="badge bg-info ms-2">{{ strtoupper($transaction->metode_pembayaran) }}</span>
                                        </p>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $transaction->confirmed_at ? \Carbon\Carbon::parse($transaction->confirmed_at)->format('d F Y H:i') : '' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($transaction->status == 'batal')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="fw-bold mb-2">Transaksi Dibatalkan</h5>
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-user me-2"></i> {{ $transaction->cancelledBy->name ?? 'System' }}
                                        </p>
                                        <p class="mb-0">
                                            <span class="badge bg-danger">Batal</span>
                                        </p>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $transaction->cancelled_at ? \Carbon\Carbon::parse($transaction->cancelled_at)->format('d F Y H:i') : '' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== MODAL KONFIRMASI PEMBAYARAN ========== -->
<div class="modal fade custom-modal" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <div class="modal-header-modern bg-success text-white">
                <h5 class="modal-title-modern" id="confirmPaymentModalLabel">
                    <i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transactions.confirm', $transaction) }}" method="POST" enctype="multipart/form-data" id="confirmPaymentForm">
                @csrf
                <div class="modal-body-modern">
                    <div class="text-center mb-4">
                        <div class="modal-icon-wrapper success mx-auto">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h4 class="fw-bold mt-3">Konfirmasi Pembayaran</h4>
                        <p class="modal-text">Upload bukti pembayaran untuk mengkonfirmasi transaksi ini.</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3">
                            <i class="fas fa-image me-2"></i> Bukti Pembayaran
                        </label>
                        
                        <div class="upload-area" id="uploadArea">
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">Klik atau drag file untuk upload</div>
                            <div class="upload-hint">Format: JPG, PNG, PDF (Maks. 2MB)</div>
                            <input type="file" class="d-none" name="bukti_pembayaran" id="buktiPembayaran" accept="image/*,.pdf" required>
                        </div>
                        
                        <div id="filePreviewContainer" class="file-preview" style="display: none;">
                            <div class="file-preview-item">
                                <div class="file-preview-icon">
                                    <i class="fas fa-file-image text-primary"></i>
                                </div>
                                <div class="file-preview-info">
                                    <div class="file-preview-name" id="fileName">-</div>
                                    <div class="file-preview-size" id="fileSize">-</div>
                                </div>
                                <div class="file-preview-remove" onclick="removeFile()">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-info-circle fa-2x"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Informasi Transaksi</h6>
                                <p class="mb-0 small">
                                    Total: <strong>Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</strong><br>
                                    Metode: <strong>{{ strtoupper($transaction->metode_pembayaran) }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-modern">
                    <button type="button" class="btn btn-outline-modern btn-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-success-modern btn-modern" id="confirmSubmitBtn">
                        <i class="fas fa-check-circle me-2"></i> Konfirmasi Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== MODAL BATALKAN TRANSAKSI ========== -->
<div class="modal fade custom-modal" id="cancelTransactionModal" tabindex="-1" aria-labelledby="cancelTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <div class="modal-header-modern bg-danger text-white">
                <h5 class="modal-title-modern" id="cancelTransactionModalLabel">
                    <i class="fas fa-times-circle me-2"></i> Batalkan Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transactions.cancel', $transaction) }}" method="POST" id="cancelTransactionForm">
                @csrf
                <div class="modal-body-modern">
                    <div class="text-center mb-4">
                        <div class="modal-icon-wrapper danger mx-auto">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h4 class="fw-bold mt-3 text-danger">Batalkan Transaksi?</h4>
                        <p class="modal-text">
                            Anda akan membatalkan transaksi #TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}.
                            Stok obat akan dikembalikan secara otomatis.
                        </p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-exclamation-triangle fa-2x mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Perhatian!</h6>
                                <ul class="mb-0 small ps-3">
                                    <li>Stok obat akan dikembalikan</li>
                                    <li>Transaksi tidak dapat diproses lebih lanjut</li>
                                    <li>Status akan berubah menjadi "Batal"</li>
                                    <li class="fw-bold text-danger">Aksi ini tidak dapat dibatalkan</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-modern">
                    <button type="button" class="btn btn-outline-modern btn-modern" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </button>
                    <button type="submit" class="btn btn-danger-modern btn-modern" id="cancelSubmitBtn">
                        <i class="fas fa-times-circle me-2"></i> Ya, Batalkan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== MODAL HAPUS TRANSAKSI ========== -->
<div class="modal fade custom-modal" id="deleteTransactionModal" tabindex="-1" aria-labelledby="deleteTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <div class="modal-header-modern bg-dark text-white">
                <h5 class="modal-title-modern" id="deleteTransactionModalLabel">
                    <i class="fas fa-trash me-2"></i> Hapus Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" id="deleteTransactionForm">
                @csrf
                @method('DELETE')
                <div class="modal-body-modern">
                    <div class="text-center mb-4">
                        <div class="modal-icon-wrapper danger mx-auto" style="background: #f8d7da; color: #721c24;">
                            <i class="fas fa-trash"></i>
                        </div>
                        <h4 class="fw-bold mt-3 text-danger">Hapus Transaksi?</h4>
                        <p class="modal-text">
                            Anda akan menghapus transaksi #TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }} secara permanen.
                        </p>
                    </div>
                    
                    <div class="alert alert-danger">
                        <div class="d-flex align-items-start gap-3">
                            <i class="fas fa-exclamation-circle fa-2x mt-1"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Peringatan!</h6>
                                <ul class="mb-0 small ps-3">
                                    <li>Data transaksi akan dihapus permanen</li>
                                    <li>Stok obat akan dikembalikan</li>
                                    <li class="fw-bold">Aksi ini TIDAK DAPAT dibatalkan!</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                        <label class="form-check-label fw-bold" for="confirmDelete">
                            Saya mengerti konsekuensi dari menghapus transaksi ini
                        </label>
                    </div>
                </div>
                <div class="modal-footer-modern">
                    <button type="button" class="btn btn-outline-modern btn-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-dark btn-modern" id="deleteSubmitBtn" disabled>
                        <i class="fas fa-trash me-2"></i> Hapus Permanen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // ========== FILE UPLOAD HANDLER ==========
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('buktiPembayaran');
        const filePreviewContainer = document.getElementById('filePreviewContainer');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        
        if (uploadArea && fileInput) {
            // Click to upload
            uploadArea.addEventListener('click', function() {
                fileInput.click();
            });
            
            // Drag & drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('dragover');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('dragover');
                
                if (e.dataTransfer.files.length) {
                    fileInput.files = e.dataTransfer.files;
                    handleFileSelect(e.dataTransfer.files[0]);
                }
            });
            
            // File select handler
            fileInput.addEventListener('change', function(e) {
                if (this.files.length) {
                    handleFileSelect(this.files[0]);
                }
            });
        }
        
        function handleFileSelect(file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 2MB',
                    timer: 3000,
                    showConfirmButton: false
                });
                fileInput.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format File Tidak Didukung',
                    text: 'Format yang diperbolehkan: JPG, PNG, GIF, PDF',
                    timer: 3000,
                    showConfirmButton: false
                });
                fileInput.value = '';
                return;
            }
            
            // Show preview
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            
            // Update icon based on file type
            const iconElement = document.querySelector('.file-preview-icon i');
            if (file.type.includes('pdf')) {
                iconElement.className = 'fas fa-file-pdf text-danger';
            } else if (file.type.includes('image')) {
                iconElement.className = 'fas fa-file-image text-primary';
            }
            
            filePreviewContainer.style.display = 'block';
            
            // Preview image if it's an image
            if (file.type.includes('image')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewHtml = `
                        <div class="file-preview-item">
                            <div class="file-preview-icon">
                                <img src="${e.target.result}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;">
                            </div>
                            <div class="file-preview-info">
                                <div class="file-preview-name">${file.name}</div>
                                <div class="file-preview-size">${formatFileSize(file.size)}</div>
                            </div>
                            <div class="file-preview-remove" onclick="removeFile()">
                                <i class="fas fa-times"></i>
                            </div>
                        </div>
                    `;
                    filePreviewContainer.innerHTML = previewHtml;
                };
                reader.readAsDataURL(file);
            }
        }
        
        // Delete confirmation checkbox
        const confirmDelete = document.getElementById('confirmDelete');
        const deleteSubmitBtn = document.getElementById('deleteSubmitBtn');
        
        if (confirmDelete && deleteSubmitBtn) {
            confirmDelete.addEventListener('change', function() {
                deleteSubmitBtn.disabled = !this.checked;
            });
        }
    });
    
    // ========== REMOVE FILE FUNCTION ==========
    window.removeFile = function() {
        const fileInput = document.getElementById('buktiPembayaran');
        const filePreviewContainer = document.getElementById('filePreviewContainer');
        
        if (fileInput) fileInput.value = '';
        if (filePreviewContainer) filePreviewContainer.style.display = 'none';
    };
    
    // ========== FORMAT FILE SIZE ==========
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // ========== SWEET ALERT CONFIRMATIONS ==========
    // Confirm Payment Form Submit
    document.getElementById('confirmPaymentForm')?.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('confirmSubmitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengkonfirmasi...';
        submitBtn.disabled = true;
    });
    
    // Cancel Transaction Form Submit
    document.getElementById('cancelTransactionForm')?.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('cancelSubmitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Membatalkan...';
        submitBtn.disabled = true;
    });
    
    // Delete Transaction Form Submit
    document.getElementById('deleteTransactionForm')?.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('deleteSubmitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menghapus...';
        submitBtn.disabled = true;
    });
    
    // ========== AUTO REFRESH FOR WAITING STATUS ==========
    @if($transaction->status === 'menunggu')
    let refreshInterval = setInterval(function() {
        if (document.visibilityState === 'visible') {
            location.reload();
        }
    }, 30000);
    
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'hidden') {
            clearInterval(refreshInterval);
        }
    });
    @endif
    
    // ========== TOOLTIP INITIALIZATION ==========
    document.addEventListener('DOMContentLoaded', function() {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    });
</script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
</script>
@endif
@endsection