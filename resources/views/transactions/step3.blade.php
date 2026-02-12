@extends('layouts.app')

@section('title', 'Pembayaran - Transaksi')

@section('styles')
<style>
    /* ========== STEP INDICATOR ========== */
    .steps {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2.5rem;
    }
    
    .step {
        text-align: center;
        position: relative;
        min-width: 160px;
    }
    
    .step-circle {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(145deg, #f8f9fa, #e9ecef);
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.3rem;
        margin: 0 auto 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .step.completed .step-circle {
        background: linear-gradient(145deg, #198754, #146c43);
        color: white;
        border-color: #198754;
    }
    
    .step.active .step-circle {
        background: linear-gradient(145deg, #0d6efd, #0b5ed7);
        color: white;
        transform: scale(1.15);
        box-shadow: 0 0 0 6px rgba(13, 110, 253, 0.15);
        border-color: white;
    }
    
    .step-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #6c757d;
        letter-spacing: 0.3px;
    }
    
    .step.active .step-label {
        color: #0d6efd;
    }
    
    .step.completed .step-label {
        color: #198754;
    }
    
    .step-line {
        width: 140px;
        height: 3px;
        background: linear-gradient(90deg, #198754, #0d6efd);
        margin: 0 15px;
        position: relative;
        top: -30px;
        opacity: 0.3;
    }
    
    .step.completed ~ .step-line {
        opacity: 1;
    }
    
    /* ========== QRIS SECTION ========== */
    .qris-section {
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border-radius: 20px;
        padding: 24px;
        margin-top: 20px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .qris-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
    }
    
    .qris-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px dashed #e2e8f0;
    }
    
    .qris-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(145deg, #3b82f6, #2563eb);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }
    
    .qris-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .qris-subtitle {
        color: #64748b;
        font-size: 0.85rem;
    }
    
    .qris-amount {
        background: linear-gradient(145deg, #e6f3ff, #d4e9ff);
        padding: 16px;
        border-radius: 16px;
        text-align: center;
        margin-bottom: 24px;
    }
    
    .qris-amount-label {
        color: #2563eb;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    
    .qris-amount-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    
    .qris-barcode-container {
        background: white;
        padding: 24px;
        border-radius: 16px;
        text-align: center;
        margin-bottom: 24px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
    
    .qris-barcode {
        max-width: 240px;
        margin: 0 auto 16px;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .qris-barcode:hover {
        transform: scale(1.05);
    }
    
    .qris-barcode svg {
        width: 100%;
        height: auto;
    }
    
    .qris-merchant {
        background: #f8fafc;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 20px;
    }
    
    .qris-merchant-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .qris-merchant-item:last-child {
        border-bottom: none;
    }
    
    .qris-merchant-label {
        color: #64748b;
        font-weight: 500;
    }
    
    .qris-merchant-value {
        color: #0f172a;
        font-weight: 600;
    }
    
    .qris-timer {
        background: linear-gradient(145deg, #fee2e2, #fecaca);
        padding: 12px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }
    
    .qris-timer i {
        color: #dc2626;
        font-size: 1.1rem;
    }
    
    .qris-timer span {
        color: #991b1b;
        font-weight: 700;
    }
    
    .qris-instruction {
        background: #f1f5f9;
        padding: 16px;
        border-radius: 12px;
        font-size: 0.9rem;
        color: #334155;
        margin-bottom: 20px;
    }
    
    .qris-instruction ol {
        margin-bottom: 0;
        padding-left: 20px;
    }
    
    .qris-instruction li {
        margin-bottom: 8px;
    }
    
    /* ========== PAYMENT METHODS ========== */
    .payment-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 24px;
    }
    
    .payment-method-item {
        position: relative;
    }
    
    .btn-check {
        position: absolute;
        opacity: 0;
    }
    
    .btn-payment {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 16px 12px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        transition: all 0.2s ease;
        cursor: pointer;
        width: 100%;
        gap: 10px;
    }
    
    .btn-payment i {
        font-size: 1.5rem;
        color: #64748b;
        transition: all 0.2s ease;
    }
    
    .btn-payment span {
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
    }
    
    .btn-check:checked + .btn-payment {
        background: linear-gradient(145deg, #0d6efd, #0b5ed7);
        border-color: #0d6efd;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(13, 110, 253, 0.2);
    }
    
    .btn-check:checked + .btn-payment i,
    .btn-check:checked + .btn-payment span {
        color: white;
    }
    
    .btn-check:disabled + .btn-payment {
        opacity: 0.6;
        cursor: not-allowed;
        background: #f1f5f9;
    }
    
    /* ========== STATUS BADGE ========== */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 600;
        background: #fef9c3;
        color: #854d0e;
        border: 1px solid #fde047;
    }
    
    .status-badge i {
        margin-right: 8px;
        font-size: 0.9rem;
    }
    
    /* ========== PROOF PREVIEW ========== */
    .proof-preview {
        background: #f8fafc;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 16px;
        margin-top: 12px;
        transition: all 0.2s ease;
    }
    
    .proof-preview:hover {
        border-color: #0d6efd;
        background: #f1f5f9;
    }
    
    /* ========== ANIMATIONS ========== */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .slide-down {
        animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .timer-pulse {
        animation: pulse 1.5s infinite;
    }
    
    /* ========== RESPONSIVE ========== */
    @media (max-width: 768px) {
        .steps {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .step {
            min-width: 100%;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .step-circle {
            margin-bottom: 0;
            width: 44px;
            height: 44px;
            font-size: 1.1rem;
        }
        
        .step-label {
            margin-bottom: 0;
        }
        
        .step-line {
            display: none;
        }
        
        .payment-methods {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .qris-amount-value {
            font-size: 1.8rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Header dengan Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('transactions.step2', $visit) }}" class="btn btn-outline-secondary btn-lg rounded-circle" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="h2 mb-1 fw-bold text-primary">
                            <i class="fas fa-credit-card me-2"></i> Pembayaran
                        </h1>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-info text-white p-2 fs-6">
                                <i class="fas fa-id-card me-2"></i> {{ $visit->patient->no_rekam_medis }}
                            </span>
                            <span class="status-badge">
                                <i class="fas fa-clock"></i> Menunggu Pembayaran
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Step Indicator Modern -->
    <div class="steps mb-5">
        <div class="step completed">
            <div class="step-circle">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Pilih Kunjungan</div>
        </div>
        <div class="step-line"></div>
        <div class="step completed">
            <div class="step-circle">
                <i class="fas fa-check"></i>
            </div>
            <div class="step-label">Tambah Item</div>
        </div>
        <div class="step-line"></div>
        <div class="step active">
            <div class="step-circle">3</div>
            <div class="step-label">Pembayaran</div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row g-4">
        <!-- Ringkasan Transaksi -->
        <div class="col-lg-6">
            <div class="card h-100 border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-receipt me-2"></i> Ringkasan Transaksi
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Info Pasien -->
                    <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold">{{ $visit->patient->nama }}</h4>
                            <div class="d-flex gap-3">
                                <span class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i> {{ $visit->patient->umur }} thn
                                </span>
                                <span class="text-muted">
                                    <i class="fas fa-user-md me-1"></i> {{ $visit->doctor->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Daftar Item -->
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-shopping-cart me-2"></i> Detail Item
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <span class="fw-medium">{{ $item['name'] }}</span>
                                        @if($item['note'])
                                            <br><small class="text-muted">{{ $item['note'] }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary bg-opacity-10 px-3 py-2">
                                            {{ $item['quantity'] }}
                                        </span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td class="text-end fw-medium">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end">
                                        <strong class="fs-5">Total</strong>
                                    </td>
                                    <td class="text-end">
                                        <h4 class="text-primary mb-0 fw-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <!-- Info Tambahan -->
                    <div class="alert alert-info bg-opacity-10 border-0 mt-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-25 p-3 rounded-circle">
                                <i class="fas fa-info-circle fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Periksa Kembali Pesanan</h6>
                                <p class="mb-0 small">Pastikan semua item dan jumlah sudah benar sebelum melanjutkan pembayaran.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Pembayaran -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-success text-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-credit-card me-2"></i> Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('transactions.store', $visit) }}" id="paymentForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Hidden fields for default status -->
                        <input type="hidden" name="status" value="menunggu">
                        <input type="hidden" name="amount_paid" id="hidden_amount_paid" value="{{ $totalAmount }}">
                        
                        <!-- Metode Pembayaran Modern -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted mb-3">
                                <i class="fas fa-credit-card me-2"></i> Pilih Metode Pembayaran
                            </label>
                            <div class="payment-methods">
                                <div class="payment-method-item">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" value="tunai" id="tunai" checked>
                                    <label class="btn-payment" for="tunai">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span>Tunai</span>
                                    </label>
                                </div>
                                <div class="payment-method-item">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" value="transfer" id="transfer">
                                    <label class="btn-payment" for="transfer">
                                        <i class="fas fa-university"></i>
                                        <span>Transfer</span>
                                    </label>
                                </div>
                                <div class="payment-method-item">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" value="qris" id="qris">
                                    <label class="btn-payment" for="qris">
                                        <i class="fas fa-qrcode"></i>
                                        <span>QRIS</span>
                                    </label>
                                </div>
                                <div class="payment-method-item">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" value="e-wallet" id="ewallet">
                                    <label class="btn-payment" for="ewallet">
                                        <i class="fas fa-wallet"></i>
                                        <span>E-Wallet</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- QRIS Section - Hidden by default, appears when QRIS selected -->
                        <div id="qrisSection" class="qris-section slide-down mb-4" style="display: none;">
                            <div class="qris-header">
                                <div class="qris-icon">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <div>
                                    <div class="qris-title">Pembayaran QRIS</div>
                                    <div class="qris-subtitle">Scan barcode untuk membayar</div>
                                </div>
                            </div>
                            
                            <div class="qris-amount">
                                <div class="qris-amount-label">Total Pembayaran</div>
                                <div class="qris-amount-value">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                            </div>
                            
                            <div class="qris-barcode-container">
                                <div class="qris-barcode" onclick="copyQrisCode()">
                                    <!-- SVG QR Code Placeholder - Generate actual QR code in production -->
                                    <svg viewBox="0 0 200 200" width="200" height="200">
                                        <rect width="200" height="200" fill="white"/>
                                        <g fill="black">
                                            <!-- QR Code Pattern - This is just a placeholder, use real QR generator -->
                                            @for($i=0; $i<40; $i++)
                                                @for($j=0; $j<40; $j++)
                                                    @if(rand(0,1))
                                                        <rect x="{{ 5 + $i*5 }}" y="{{ 5 + $j*5 }}" width="4" height="4"/>
                                                    @endif
                                                @endfor
                                            @endfor
                                        </g>
                                    </svg>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyQrisCode()">
                                    <i class="fas fa-copy me-2"></i> Salin Kode QR
                                </button>
                            </div>
                            
                            <div class="qris-merchant">
                                <div class="qris-merchant-item">
                                    <span class="qris-merchant-label">Merchant</span>
                                    <span class="qris-merchant-value">KLINIK SEHAT</span>
                                </div>
                                <div class="qris-merchant-item">
                                    <span class="qris-merchant-label">ID Merchant</span>
                                    <span class="qris-merchant-value">ID1234567890</span>
                                </div>
                                <div class="qris-merchant-item">
                                    <span class="qris-merchant-label">Nama</span>
                                    <span class="qris-merchant-value">{{ $visit->patient->nama }}</span>
                                </div>
                                <div class="qris-merchant-item">
                                    <span class="qris-merchant-label">No. Kunjungan</span>
                                    <span class="qris-merchant-value">#{{ $visit->id }}</span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="qris-timer timer-pulse">
                                    <i class="fas fa-hourglass-half"></i>
                                    <span id="qrisTimer">10:00</span>
                                </div>
                                <span class="text-muted small">
                                    <i class="fas fa-shield-alt me-1"></i> Transaksi Aman
                                </span>
                            </div>
                            
                            <div class="qris-instruction">
                                <strong>Cara Pembayaran:</strong>
                                <ol class="mt-2">
                                    <li>Buka aplikasi mobile banking atau e-wallet Anda</li>
                                    <li>Pilih menu scan QRIS atau bayar QR</li>
                                    <li>Scan barcode di atas</li>
                                    <li>Periksa nominal Rp {{ number_format($totalAmount, 0, ',', '.') }}</li>
                                    <li>Masukkan PIN dan selesaikan pembayaran</li>
                                    <li>Simpan bukti pembayaran untuk verifikasi</li>
                                </ol>
                            </div>
                        </div>
                        
                        <!-- Bukti Pembayaran Section (Optional) -->
                        <div class="mb-4" id="proofSection" style="display: none;">
                            {{-- <label class="form-label fw-bold text-muted">
                                <i class="fas fa-image me-2"></i> Upload Bukti Pembayaran (Opsional)
                            </label>
                            <div class="upload-area" id="uploadArea">
                                <input type="file" class="form-control" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*,.pdf">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i> Upload screenshot atau foto bukti transfer (maks. 2MB)
                                </div>
                            </div> --}}
                            <div id="proofPreview" class="proof-preview" style="display: none;"></div>
                        </div>
                        
                        <!-- Informasi Status Otomatis -->
                        <div class="alert alert-warning bg-opacity-10 border-0 mt-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1">Status: Menunggu Pembayaran</h6>
                                    <p class="mb-0 small">Transaksi akan otomatis menunggu pembayaran. Admin akan memverifikasi setelah bukti pembayaran diupload.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi Modern -->
                        <div class="d-grid gap-3 mt-4">
                            {{-- <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" name="print_invoice" id="print_invoice" checked>
                                <label class="form-check-label" for="print_invoice">
                                    <i class="fas fa-print me-2"></i> Cetak invoice setelah simpan
                                </label>
                            </div> --}}
                            
                            <button type="submit" class="btn btn-success btn-lg fw-semibold py-3" id="submitBtn">
                                <i class="fas fa-check-circle me-2"></i> Buat Transaksi
                                <span class="ms-2" id="submitStatusText">(Menunggu Pembayaran)</span>
                            </button>
                            
                            <a href="{{ route('transactions.step2', $visit) }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Item
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const totalAmount = {{ $totalAmount }};
    let qrisTimer;
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        initPaymentMethods();
    });
    
    function initPaymentMethods() {
        // Handle payment method change
        $('input[name="metode_pembayaran"]').change(function() {
            const method = $(this).val();
            
            // Hide all dynamic sections
            $('#qrisSection').hide();
            $('#proofSection').hide();
            $('#bukti_pembayaran').prop('required', false);
            
            if (method === 'qris') {
                $('#qrisSection').slideDown();
                $('#proofSection').slideDown();
                startQrisTimer();
            } else if (method === 'tunai') {
                $('#proofSection').hide();
            } else {
                $('#proofSection').slideDown();
            }
            
            // Update submit button text
            updateSubmitButtonText(method);
        });
    }
    
    function updateSubmitButtonText(method) {
        let statusText = '';
        
        switch(method) {
            case 'tunai':
                statusText = '(Menunggu Pembayaran Tunai)';
                break;
            case 'qris':
                statusText = '(Menunggu Pembayaran QRIS)';
                break;
            default:
                statusText = '(Menunggu Verifikasi Bukti)';
        }
        
        $('#submitStatusText').text(statusText);
    }
    
    function startQrisTimer() {
        // Clear existing timer
        if (qrisTimer) clearInterval(qrisTimer);
        
        let timeLeft = 600; // 10 minutes in seconds
        const timerElement = document.getElementById('qrisTimer');
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(qrisTimer);
                timerElement.textContent = '00:00';
                // Optional: Show expired message
                $('.qris-timer').removeClass('timer-pulse').addClass('bg-danger text-white');
            }
            
            timeLeft--;
        }
        
        updateTimer();
        qrisTimer = setInterval(updateTimer, 1000);
    }
    
    function copyQrisCode() {
        // Implement QR code copy functionality
        const tempInput = document.createElement('input');
        tempInput.value = 'QRIS-MERCHANT-KLINIKSEHAT-{{ $visit->id }}';
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        // Show feedback
        Swal.fire({
            icon: 'success',
            title: 'Tersalin!',
            text: 'Kode QR berhasil disalin',
            timer: 1500,
            showConfirmButton: false
        });
    }
    
    // File upload preview
    $('#bukti_pembayaran').change(function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const preview = $('#proofPreview');
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.html(`
                        <div class="d-flex align-items-center gap-3">
                            <img src="${e.target.result}" class="rounded" style="max-width: 80px; max-height: 80px;">
                            <div>
                                <strong class="d-block">${file.name}</strong>
                                <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                            </div>
                        </div>
                    `).show();
                }
                reader.readAsDataURL(file);
            } else {
                preview.html(`
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-file-pdf fa-2x text-danger"></i>
                        <div>
                            <strong class="d-block">${file.name}</strong>
                            <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                        </div>
                    </div>
                `).show();
            }
        }
    });
    
    // Form submission with loading state
    $('#paymentForm').submit(function(e) {
        const submitBtn = $('#submitBtn');
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses Transaksi...').prop('disabled', true);
    });
    
    // Clean up timer on page unload
    window.addEventListener('beforeunload', function() {
        if (qrisTimer) clearInterval(qrisTimer);
    });
</script>
@endsection