@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="dashboard-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard Kasir</h1>
            <p class="page-subtitle">{{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickPaymentModal">
                <i class="fas fa-bolt me-2"></i>Pembayaran Cepat
            </button>
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Transaksi Baru
            </a>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Transaksi Hari Ini</div>
                <div class="stat-value">{{ number_format($stats['todayTransactions']) }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-primary">
                        <i class="fas fa-exchange-alt me-1"></i>Total Transaksi
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Pendapatan Hari Ini</div>
                <div class="stat-value" style="font-size: 20px;">Rp {{ number_format($stats['todayIncome'], 0, ',', '.') }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-success">
                        <i class="fas fa-arrow-up me-1"></i>Sudah Diterima
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-warning">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Menunggu Pembayaran</div>
                <div class="stat-value">{{ number_format($stats['pendingTransactions']) }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-warning">
                        <i class="fas fa-clock me-1"></i>Belum Lunas
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Rata-rata Transaksi</div>
                <div class="stat-value" style="font-size: 18px;">Rp {{ number_format($stats['avgTransaction'], 0, ',', '.') }}</div>
                <div class="stat-meta">
                    <span class="badge badge-soft-info">
                        <i class="fas fa-calculator me-1"></i>Per Transaksi
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Pending Payments -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Menunggu Pembayaran</h5>
                    <small class="text-muted">{{ count($stats['pendingPayments']) }} pembayaran tertunda</small>
                </div>
                <button class="btn btn-sm btn-outline-secondary" onclick="refreshPendingPayments()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div class="card-body p-0">
                <div class="pending-payments-list" style="max-height: 500px; overflow-y: auto;">
                    @forelse($stats['pendingPayments'] as $transaction)
                    <div class="pending-payment-item">
                        <div class="payment-patient-info">
                            <div class="patient-avatar">
                                {{ substr($transaction->visit->patient->nama, 0, 1) }}
                            </div>
                            <div class="patient-details">
                                <div class="patient-name">{{ $transaction->visit->patient->nama }}</div>
                                <div class="patient-meta">
                                    <small class="text-muted">
                                        {{ $transaction->visit->patient->no_rekam_medis }} • 
                                        <i class="fas fa-clock me-1"></i>{{ $transaction->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="payment-details">
                            <div class="payment-amount">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</div>
                            <div class="payment-method">
                                <span class="badge bg-{{ $transaction->metode_pembayaran == 'tunai' ? 'primary' : 
                                                          ($transaction->metode_pembayaran == 'transfer' ? 'success' : 
                                                          ($transaction->metode_pembayaran == 'qris' ? 'info' : 'warning')) }}">
                                    {{ strtoupper($transaction->metode_pembayaran) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="payment-action">
                            <button class="btn btn-sm btn-success" onclick="processPayment({{ $transaction->id }})">
                                <i class="fas fa-check me-1"></i>Proses
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="empty-state">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6 class="text-success">Semua Pembayaran Selesai</h6>
                        <p class="text-muted mb-0">Tidak ada pembayaran tertunda</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Tools -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Panel Pembayaran</h5>
                <small class="text-muted">Metode pembayaran & kalkulator</small>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Payment Methods -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-primary mb-3">Metode Pembayaran</h6>
                        <div class="payment-methods-grid">
                            <button class="payment-method-btn" onclick="selectPaymentMethod('tunai')">
                                <div class="payment-method-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <span>Tunai</span>
                            </button>
                            <button class="payment-method-btn" onclick="selectPaymentMethod('transfer')">
                                <div class="payment-method-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <i class="fas fa-university"></i>
                                </div>
                                <span>Transfer</span>
                            </button>
                            <button class="payment-method-btn" onclick="selectPaymentMethod('qris')">
                                <div class="payment-method-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                                    <i class="fas fa-qrcode"></i>
                                </div>
                                <span>QRIS</span>
                            </button>
                            <button class="payment-method-btn" onclick="selectPaymentMethod('e-wallet')">
                                <div class="payment-method-icon" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <span>E-Wallet</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Calculator -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Kalkulator</h6>
                        <div class="calculator-widget">
                            <div class="calculator-display">
                                <input type="text" class="form-control form-control-lg text-end" 
                                       id="calcDisplay" value="0" readonly>
                            </div>
                            <div class="calculator-buttons">
                                <div class="calc-row">
                                    <button class="calc-btn" onclick="calcInput('7')">7</button>
                                    <button class="calc-btn" onclick="calcInput('8')">8</button>
                                    <button class="calc-btn" onclick="calcInput('9')">9</button>
                                    <button class="calc-btn calc-btn-danger" onclick="calcClear()">C</button>
                                </div>
                                <div class="calc-row">
                                    <button class="calc-btn" onclick="calcInput('4')">4</button>
                                    <button class="calc-btn" onclick="calcInput('5')">5</button>
                                    <button class="calc-btn" onclick="calcInput('6')">6</button>
                                    <button class="calc-btn" onclick="calcInput('+')">+</button>
                                </div>
                                <div class="calc-row">
                                    <button class="calc-btn" onclick="calcInput('1')">1</button>
                                    <button class="calc-btn" onclick="calcInput('2')">2</button>
                                    <button class="calc-btn" onclick="calcInput('3')">3</button>
                                    <button class="calc-btn" onclick="calcInput('-')">-</button>
                                </div>
                                <div class="calc-row">
                                    <button class="calc-btn" onclick="calcInput('0')">0</button>
                                    <button class="calc-btn" onclick="calcInput('.')">.</button>
                                    <button class="calc-btn calc-btn-primary" onclick="calcCalculate()">=</button>
                                    <button class="calc-btn calc-btn-success" onclick="copyAmount()">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Transactions & Statistics -->
<div class="row g-3 mt-2">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Transaksi Terbaru</h5>
                    <small class="text-muted">Riwayat transaksi hari ini</small>
                </div>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Waktu</th>
                                <th>Pasien</th>
                                <th>Metode</th>
                                <th class="text-end">Jumlah</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recentTransactions'] as $transaction)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $transaction->created_at->format('H:i') }}</div>
                                    <small class="text-muted">{{ $transaction->created_at->format('d/m') }}</small>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $transaction->visit->patient->nama }}</div>
                                    <small class="text-muted">{{ $transaction->visit->patient->no_rekam_medis }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $transaction->metode_pembayaran == 'tunai' ? 'primary' : 
                                                              ($transaction->metode_pembayaran == 'transfer' ? 'success' : 
                                                              ($transaction->metode_pembayaran == 'qris' ? 'info' : 'warning')) }}">
                                        {{ strtoupper($transaction->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td class="text-end fw-semibold">
                                    Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}
                                </td>
                                <td>
                                    <span class="badge badge-status-{{ $transaction->status }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-secondary" onclick="viewReceipt({{ $transaction->id }})">
                                            <i class="fas fa-receipt"></i>
                                        </button>
                                        @if($transaction->status == 'menunggu')
                                        <button class="btn btn-outline-success" onclick="confirmPayment({{ $transaction->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Belum ada transaksi hari ini</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Statistics Chart -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistik Pembayaran</h5>
                <small class="text-muted">Distribusi metode hari ini</small>
            </div>
            <div class="card-body">
                <div class="chart-container mb-4">
                    <canvas id="paymentMethodChart" height="200"></canvas>
                </div>
                
                <div class="payment-summary">
                    <div class="summary-item">
                        <span class="summary-label">Total Transaksi</span>
                        <span class="summary-value">{{ $stats['todayTransactions'] }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Total Pendapatan</span>
                        <span class="summary-value text-success">Rp {{ number_format($stats['todayIncome'], 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Rata-rata Transaksi</span>
                        <span class="summary-value">Rp {{ number_format($stats['avgTransaction'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Payment Modal -->
<div class="modal fade" id="quickPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0">Pembayaran Cepat</h5>
                    <small class="text-muted">Proses pembayaran dengan cepat</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickPaymentModalForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cari Pasien</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="modalSearchPatient" 
                                       placeholder="Nama atau No. RM..." autocomplete="off">
                            </div>
                            <div id="patientSearchResults" class="mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pilih Kunjungan</label>
                            <select class="form-select" id="visitSelect" disabled>
                                <option value="">Pilih pasien terlebih dahulu</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <div class="payment-methods-modal">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="tunai" checked>
                                <label class="form-check-label" for="cash">
                                    <i class="fas fa-money-bill-wave me-1"></i>Tunai
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="transfer" value="transfer">
                                <label class="form-check-label" for="transfer">
                                    <i class="fas fa-university me-1"></i>Transfer
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="qris" value="qris">
                                <label class="form-check-label" for="qris">
                                    <i class="fas fa-qrcode me-1"></i>QRIS
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="ewallet" value="e-wallet">
                                <label class="form-check-label" for="ewallet">
                                    <i class="fas fa-mobile-alt me-1"></i>E-Wallet
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Bayar</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="paymentAmount" 
                                       placeholder="0" min="0" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <input type="text" class="form-control" id="paymentNote" 
                                   placeholder="Catatan tambahan...">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="processQuickPayment()">
                    <i class="fas fa-check me-2"></i>Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stat-card-warning {
        animation: pulse-warning 2s infinite;
    }
    
    @keyframes pulse-warning {
        0%, 100% {
            background: white;
        }
        50% {
            background: rgba(251, 191, 36, 0.05);
        }
    }
    
    /* Pending Payments */
    .pending-payments-list {
        display: flex;
        flex-direction: column;
    }
    
    .pending-payment-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.2s ease;
        border-left: 3px solid #fbbf24;
    }
    
    .pending-payment-item:hover {
        background: var(--background);
    }
    
    .pending-payment-item:last-child {
        border-bottom: none;
    }
    
    .payment-patient-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }
    
    .payment-details {
        text-align: right;
        flex-shrink: 0;
    }
    
    .payment-amount {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
    }
    
    .payment-method {
        margin-top: 4px;
    }
    
    .payment-action {
        flex-shrink: 0;
    }
    
    /* Payment Methods Grid */
    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .payment-method-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        padding: 20px;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
        color: var(--text-primary);
    }
    
    .payment-method-btn:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .payment-method-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }
    
    /* Calculator */
    .calculator-widget {
        background: var(--background);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }
    
    .calculator-display {
        margin-bottom: 12px;
    }
    
    .calculator-display input {
        font-size: 24px;
        font-weight: 700;
        background: white;
        border: 1px solid var(--border-color);
    }
    
    .calculator-buttons {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .calc-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }
    
    .calc-btn {
        padding: 16px;
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 16px;
    }
    
    .calc-btn:hover {
        background: var(--sidebar-hover);
        border-color: var(--primary-color);
    }
    
    .calc-btn:active {
        transform: scale(0.95);
    }
    
    .calc-btn-primary {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .calc-btn-primary:hover {
        background: var(--primary-hover);
    }
    
    .calc-btn-success {
        background: #10b981;
        color: white;
        border-color: #10b981;
    }
    
    .calc-btn-success:hover {
        background: #059669;
    }
    
    .calc-btn-danger {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
    }
    
    .calc-btn-danger:hover {
        background: #dc2626;
    }
    
    /* Payment Summary */
    .payment-summary {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        background: var(--background);
        border-radius: 8px;
    }
    
    .summary-label {
        font-size: 14px;
        color: var(--text-secondary);
        font-weight: 500;
    }
    
    .summary-value {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
    }
    
    /* Modal Payment Methods */
    .payment-methods-modal {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    
    .payment-methods-modal .form-check-inline {
        margin: 0;
        flex: 1;
        min-width: 120px;
    }
    
    .payment-methods-modal .form-check-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 16px;
        background: var(--background);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
        width: 100%;
    }
    
    .payment-methods-modal .form-check-input {
        display: none;
    }
    
    .payment-methods-modal .form-check-input:checked + label {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize calculator
    window.calcValue = '0';
    window.calcOperator = '';
    window.calcPreviousValue = '';
    
    function calcInput(value) {
        if (value === '.') {
            if (!window.calcValue.includes('.')) {
                window.calcValue += '.';
            }
        } else if (value === '+' || value === '-') {
            window.calcOperator = value;
            window.calcPreviousValue = window.calcValue;
            window.calcValue = '0';
        } else {
            if (window.calcValue === '0') {
                window.calcValue = value;
            } else {
                window.calcValue += value;
            }
        }
        updateCalcDisplay();
    }
    
    function calcClear() {
        window.calcValue = '0';
        window.calcOperator = '';
        window.calcPreviousValue = '';
        updateCalcDisplay();
    }
    
    function calcCalculate() {
        if (window.calcOperator && window.calcPreviousValue) {
            const prev = parseFloat(window.calcPreviousValue);
            const current = parseFloat(window.calcValue);
            
            switch(window.calcOperator) {
                case '+':
                    window.calcValue = (prev + current).toString();
                    break;
                case '-':
                    window.calcValue = (prev - current).toString();
                    break;
            }
            
            window.calcOperator = '';
            window.calcPreviousValue = '';
            updateCalcDisplay();
        }
    }
    
    function updateCalcDisplay() {
        document.getElementById('calcDisplay').value = window.calcValue;
    }
    
    function copyAmount() {
        const amount = window.calcValue;
        navigator.clipboard.writeText(amount).then(() => {
            showNotification('Jumlah berhasil disalin: ' + amount, 'success');
        });
    }
    
    function selectPaymentMethod(method) {
        showNotification(`Metode pembayaran: ${method.toUpperCase()}`, 'info');
    }
    
    function refreshPendingPayments() {
        location.reload();
    }
    
    function processPayment(transactionId) {
        if(confirm('Proses pembayaran ini?')) {
            // Process payment logic here
            location.reload();
        }
    }
    
    function confirmPayment(transactionId) {
        if(confirm('Konfirmasi pembayaran?')) {
            // Confirm payment logic here
            location.reload();
        }
    }
    
    function viewReceipt(transactionId) {
        window.open(`/transactions/${transactionId}/receipt`, '_blank');
    }
    
    function processQuickPayment() {
        const visitId = document.getElementById('visitSelect').value;
        if (!visitId) {
            showNotification('Pilih kunjungan terlebih dahulu', 'warning');
            return;
        }
        // Process quick payment logic here
        showNotification('Pembayaran berhasil diproses', 'success');
        setTimeout(() => location.reload(), 1500);
    }
    
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }
    
    // Payment chart
    const ctx = document.getElementById('paymentMethodChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Tunai', 'Transfer', 'QRIS', 'E-Wallet'],
            datasets: [{
                data: [
                    {{ $stats['cashAmount'] ?? 0 }},
                    {{ $stats['transferAmount'] ?? 0 }},
                    {{ $stats['qrisAmount'] ?? 0 }},
                    {{ $stats['ewalletAmount'] ?? 0 }}
                ],
                backgroundColor: ['#6366f1', '#10b981', '#3b82f6', '#fbbf24'],
                borderWidth: 0,
                borderRadius: 4,
                spacing: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: { size: 12 }
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection