@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-cash-register me-2"></i>Dashboard Kasir
            </h1>
            <div class="btn-group">
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Transaksi Baru
                </a>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#quickPaymentModal">
                    <i class="fas fa-bolt me-2"></i>Pembayaran Cepat
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Kasir -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Transaksi Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['todayTransactions'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-primary">
                                <i class="fas fa-exchange-alt"></i> Total transaksi
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Pendapatan Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['todayIncome'], 0, ',', '.') }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success">
                                <i class="fas fa-money-bill-wave"></i> Sudah diterima
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wallet fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Menunggu Pembayaran
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pendingTransactions'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-warning">
                                <i class="fas fa-clock"></i> Belum lunas
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Rata-rata Transaksi
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['avgTransaction'], 0, ',', '.') }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-info">
                                <i class="fas fa-calculator"></i> Per transaksi
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Panel Utama Kasir -->
<div class="row mb-4">
    <!-- Pending Payments -->
    <div class="col-lg-5">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-clock me-2"></i>Menunggu Pembayaran
                </h6>
                <button class="btn btn-sm btn-outline-warning" onclick="refreshPendingPayments()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                <div id="pendingPaymentsList">
                    @foreach($stats['pendingPayments'] as $transaction)
                    <div class="card mb-2 border-left-warning">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $transaction->visit->patient->nama }}</h6>
                                    <small class="text-muted">
                                        No. RM: {{ $transaction->visit->patient->no_rekam_medis }}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $transaction->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-0 text-danger">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</h6>
                                    <small class="badge bg-{{ $transaction->metode_pembayaran == 'tunai' ? 'primary' : 
                                                              ($transaction->metode_pembayaran == 'transfer' ? 'success' : 
                                                              ($transaction->metode_pembayaran == 'qris' ? 'info' : 'warning')) }}">
                                        {{ strtoupper($transaction->metode_pembayaran) }}
                                    </small>
                                    <br>
                                    <button class="btn btn-sm btn-success mt-1" onclick="processPayment({{ $transaction->id }})">
                                        <i class="fas fa-check me-1"></i>Proses
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if(empty($stats['pendingPayments']))
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h6 class="text-success">Tidak ada pembayaran tertunda</h6>
                    <p class="text-muted">Semua transaksi telah diproses</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions & Calculator -->
    <div class="col-lg-7">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>Panel Cepat Kasir
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Payment Methods -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-primary mb-3">Metode Pembayaran</h6>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary btn-lg" onclick="selectPaymentMethod('tunai')">
                                <i class="fas fa-money-bill-wave me-2"></i>Tunai
                            </button>
                            <button class="btn btn-outline-success btn-lg" onclick="selectPaymentMethod('transfer')">
                                <i class="fas fa-university me-2"></i>Transfer
                            </button>
                            <button class="btn btn-outline-info btn-lg" onclick="selectPaymentMethod('qris')">
                                <i class="fas fa-qrcode me-2"></i>QRIS
                            </button>
                            <button class="btn btn-outline-warning btn-lg" onclick="selectPaymentMethod('e-wallet')">
                                <i class="fas fa-mobile-alt me-2"></i>E-Wallet
                            </button>
                        </div>
                    </div>
                    
                    <!-- Calculator -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3">Kalkulator</h6>
                        <div class="calculator">
                            <div class="mb-3">
                                <input type="text" class="form-control form-control-lg text-end" 
                                       id="calcDisplay" value="0" readonly>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('7')">7</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('8')">8</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('9')">9</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-danger w-100" onclick="calcClear()">C</button>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('4')">4</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('5')">5</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('6')">6</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('+')">+</button>
                                </div>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('1')">1</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('2')">2</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('3')">3</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('-')">-</button>
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('0')">0</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-secondary w-100" onclick="calcInput('.')">.</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-primary w-100" onclick="calcCalculate()">=</button>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-outline-success w-100" onclick="copyAmount()">Copy</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Payment Form -->
                <div class="mt-4">
                    <h6 class="text-primary mb-3">Pembayaran Cepat</h6>
                    <form id="quickPaymentForm" class="row g-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchPatient" 
                                   placeholder="Cari nama pasien atau no. RM...">
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control" id="amount" 
                                   placeholder="Jumlah" min="0" step="1000">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-bolt me-2"></i>Bayar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaksi Terbaru -->
<div class="row">
    <div class="col-lg-7">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Transaksi Terbaru
                </h6>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-list me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pasien</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stats['recentTransactions'] as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('H:i') }}</td>
                                <td>{{ $transaction->visit->patient->nama }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->metode_pembayaran == 'tunai' ? 'primary' : 
                                                              ($transaction->metode_pembayaran == 'transfer' ? 'success' : 
                                                              ($transaction->metode_pembayaran == 'qris' ? 'info' : 'warning')) }}">
                                        {{ strtoupper($transaction->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td class="text-end">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->status == 'lunas' ? 'success' : 
                                                              ($transaction->status == 'menunggu' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" onclick="viewReceipt({{ $transaction->id }})">
                                        <i class="fas fa-receipt"></i>
                                    </button>
                                    @if($transaction->status == 'menunggu')
                                    <button class="btn btn-sm btn-success" onclick="confirmPayment({{ $transaction->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Statistics -->
    <div class="col-lg-5">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-pie me-2"></i>Statistik Pembayaran Hari Ini
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4">
                    <canvas id="paymentMethodChart" height="200"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-3">
                        <i class="fas fa-circle text-primary"></i> Tunai
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-circle text-success"></i> Transfer
                    </span>
                    <span class="mr-3">
                        <i class="fas fa-circle text-info"></i> QRIS
                    </span>
                    <span>
                        <i class="fas fa-circle text-warning"></i> E-Wallet
                    </span>
                </div>
                <div class="mt-4">
                    <h6 class="text-primary">Ringkasan</h6>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Total Transaksi</span>
                            <span class="font-weight-bold">{{ $stats['todayTransactions'] }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Total Pendapatan</span>
                            <span class="font-weight-bold text-success">
                                Rp {{ number_format($stats['todayIncome'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>Rata-rata per Transaksi</span>
                            <span class="font-weight-bold">
                                Rp {{ number_format($stats['avgTransaction'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran Cepat -->
<div class="modal fade" id="quickPaymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-bolt me-2"></i>Pembayaran Cepat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickPaymentModalForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Cari Pasien</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="modalSearchPatient" 
                                       placeholder="Nama atau No. RM..." autocomplete="off">
                                <button class="btn btn-outline-primary" type="button" onclick="searchPatientModal()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                            <div id="patientSearchResults" class="mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pilih Kunjungan</label>
                            <select class="form-select" id="visitSelect" disabled>
                                <option value="">Pilih kunjungan terlebih dahulu</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rincian Biaya</label>
                        <div id="billDetails" class="card">
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="billItems">
                                        <!-- Bill items will be added here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">Total</th>
                                            <th id="billTotal" class="text-end">Rp 0</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="paymentMethod" 
                                       id="cash" value="tunai" checked>
                                <label class="btn btn-outline-primary" for="cash">Tunai</label>
                                
                                <input type="radio" class="btn-check" name="paymentMethod" 
                                       id="transfer" value="transfer">
                                <label class="btn btn-outline-success" for="transfer">Transfer</label>
                                
                                <input type="radio" class="btn-check" name="paymentMethod" 
                                       id="qris" value="qris">
                                <label class="btn btn-outline-info" for="qris">QRIS</label>
                                
                                <input type="radio" class="btn-check" name="paymentMethod" 
                                       id="ewallet" value="e-wallet">
                                <label class="btn btn-outline-warning" for="ewallet">E-Wallet</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Bayar</label>
                            <input type="number" class="form-control" id="paymentAmount" 
                                   placeholder="Jumlah yang dibayarkan" min="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="paymentNote" rows="2" 
                                  placeholder="Catatan tambahan..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="processQuickPayment()">
                    <i class="fas fa-check me-2"></i>Proses Pembayaran
                </button>
                <button type="button" class="btn btn-success" onclick="printReceipt()">
                    <i class="fas fa-print me-2"></i>Cetak Struk
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .calculator {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #e3e6f0;
    }
    
    .calculator input {
        font-size: 1.5rem;
        font-weight: bold;
        background-color: white;
    }
    
    .payment-method-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .transaction-row {
        transition: all 0.3s;
        cursor: pointer;
    }
    
    .transaction-row:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
    
    .pending-payment-card {
        border-left: 4px solid #f6c23e;
        animation: pulse-warning 2s infinite;
    }
    
    @keyframes pulse-warning {
        0% { border-left-color: #f6c23e; }
        50% { border-left-color: #ffd700; }
        100% { border-left-color: #f6c23e; }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize calculator
        initCalculator();
        
        // Load payment statistics chart
        loadPaymentChart();
        
        // Auto-refresh pending payments every 30 seconds
        setInterval(refreshPendingPayments, 30000);
        
        // Quick payment form submission
        document.getElementById('quickPaymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            processQuickPaymentFromForm();
        });
        
        // Modal search patient input
        document.getElementById('modalSearchPatient').addEventListener('input', function(e) {
            if (e.target.value.length >= 2) {
                searchPatientModal(e.target.value);
            }
        });
        
        function initCalculator() {
            window.calcDisplay = document.getElementById('calcDisplay');
            window.calcValue = '0';
            window.calcOperator = '';
            window.calcPreviousValue = '';
            
            updateCalcDisplay();
        }
        
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
            window.calcDisplay.value = window.calcValue;
        }
        
        function copyAmount() {
            const amount = window.calcValue;
            navigator.clipboard.writeText(amount).then(() => {
                showNotification('Jumlah berhasil disalin: ' + amount, 'success');
            });
        }
        
        function selectPaymentMethod(method) {
            const buttons = document.querySelectorAll('[name="paymentMethod"]');
            buttons.forEach(btn => {
                if (btn.value === method) {
                    btn.checked = true;
                    btn.parentElement.click();
                }
            });
            
            showNotification(`Metode pembayaran: ${method.toUpperCase()}`, 'info');
        }
        
        function refreshPendingPayments() {
            fetch('/api/transactions/pending')
                .then(response => response.json())
                .then(data => {
                    updatePendingPaymentsList(data);
                })
                .catch(error => console.error('Error refreshing pending payments:', error));
        }
        
        function updatePendingPaymentsList(payments) {
            const container = document.getElementById('pendingPaymentsList');
            
            if (payments.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h6 class="text-success">Tidak ada pembayaran tertunda</h6>
                    </div>
                `;
                return;
            }
            
            let html = '';
            payments.forEach(payment => {
                html += `
                    <div class="card mb-2 border-left-warning pending-payment-card">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">${payment.patient_name}</h6>
                                    <small class="text-muted">
                                        No. RM: ${patient.patient_rm}
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        ${payment.time_ago}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <h6 class="mb-0 text-danger">Rp ${formatCurrency(payment.amount)}</h6>
                                    <small class="badge bg-${payment.method === 'tunai' ? 'primary' : 
                                                              (payment.method === 'transfer' ? 'success' : 
                                                              (payment.method === 'qris' ? 'info' : 'warning'))}">
                                        ${payment.method.toUpperCase()}
                                    </small>
                                    <br>
                                    <button class="btn btn-sm btn-success mt-1" onclick="processPayment(${payment.id})">
                                        <i class="fas fa-check me-1"></i>Proses
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        function processPayment(transactionId) {
            fetch(`/api/transactions/${transactionId}/process`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Pembayaran berhasil diproses', 'success');
                    refreshPendingPayments();
                    // Update recent transactions
                    loadRecentTransactions();
                }
            });
        }
        
        function confirmPayment(transactionId) {
            fetch(`/transactions/${transactionId}/confirm`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Pembayaran dikonfirmasi', 'success');
                    // Refresh data
                    location.reload();
                }
            });
        }
        
        function viewReceipt(transactionId) {
            window.open(`/transactions/${transactionId}/receipt`, '_blank');
        }
        
        function searchPatientModal(query = null) {
            const searchTerm = query || document.getElementById('modalSearchPatient').value;
            
            if (searchTerm.length < 2) {
                showNotification('Masukkan minimal 2 karakter', 'warning');
                return;
            }
            
            fetch(`/api/patients/search?q=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    displayPatientResults(data);
                });
        }
        
        function displayPatientResults(patients) {
            const container = document.getElementById('patientSearchResults');
            
            if (patients.length === 0) {
                container.innerHTML = '<div class="alert alert-info">Pasien tidak ditemukan</div>';
                return;
            }
            
            let html = '';
            patients.forEach(patient => {
                html += `
                    <div class="card mb-2">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">${patient.nama}</h6>
                                    <small class="text-muted">${patient.no_rekam_medis}</small>
                                </div>
                                <button class="btn btn-sm btn-primary" onclick="selectPatient(${patient.id})">
                                    <i class="fas fa-check"></i> Pilih
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        function selectPatient(patientId) {
            fetch(`/api/patients/${patientId}/visits/unpaid`)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('visitSelect');
                    select.disabled = false;
                    select.innerHTML = '<option value="">Pilih kunjungan...</option>';
                    
                    data.forEach(visit => {
                        const option = document.createElement('option');
                        option.value = visit.id;
                        option.textContent = `Kunjungan ${visit.date} - Rp ${formatCurrency(visit.total_amount)}`;
                        option.setAttribute('data-bill', JSON.stringify(visit.bill_details));
                        select.appendChild(option);
                    });
                    
                    document.getElementById('patientSearchResults').innerHTML = '';
                    document.getElementById('modalSearchPatient').value = '';
                });
        }
        
        document.getElementById('visitSelect').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const billDetails = JSON.parse(selectedOption.getAttribute('data-bill'));
                updateBillDetails(billDetails);
            }
        });
        
        function updateBillDetails(billItems) {
            const container = document.getElementById('billItems');
            let total = 0;
            
            container.innerHTML = '';
            billItems.forEach(item => {
                const subtotal = item.quantity * item.price;
                total += subtotal;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.description}</td>
                    <td>${item.quantity}</td>
                    <td class="text-end">Rp ${formatCurrency(item.price)}</td>
                    <td class="text-end">Rp ${formatCurrency(subtotal)}</td>
                `;
                container.appendChild(row);
            });
            
            document.getElementById('billTotal').textContent = `Rp ${formatCurrency(total)}`;
            document.getElementById('paymentAmount').value = total;
        }
        
        function processQuickPayment() {
            const visitId = document.getElementById('visitSelect').value;
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            const paymentAmount = document.getElementById('paymentAmount').value;
            const note = document.getElementById('paymentNote').value;
            
            if (!visitId) {
                showNotification('Pilih kunjungan terlebih dahulu', 'warning');
                return;
            }
            
            fetch('/api/transactions/quick-pay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    visit_id: visitId,
                    method: paymentMethod,
                    amount: paymentAmount,
                    note: note
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Pembayaran berhasil diproses', 'success');
                    
                    // Reset form
                    document.getElementById('quickPaymentModalForm').reset();
                    document.getElementById('billItems').innerHTML = '';
                    document.getElementById('billTotal').textContent = 'Rp 0';
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('quickPaymentModal')).hide();
                    
                    // Refresh data
                    location.reload();
                }
            });
        }
        
        function printReceipt() {
            // Implement receipt printing
            window.print();
        }
        
        function loadPaymentChart() {
            const ctx = document.getElementById('paymentMethodChart').getContext('2d');
            const paymentChart = new Chart(ctx, {
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
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a'],
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
        
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
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
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    });
</script>
@endsection