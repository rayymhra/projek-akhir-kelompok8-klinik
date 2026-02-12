@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('styles')
<style>
    /* Simplified Stat Cards */
    .cashier-stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 20px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .cashier-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .stat-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        color: white;
        font-size: 1.25rem;
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-label {
        font-size: 12px;
        color: var(--text-secondary);
        font-weight: 500;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.2;
    }
    
    .stat-currency {
        font-size: 16px;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    /* Pending Payments */
    .pending-payment-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        border-left: 4px solid #fbbf24;
    }
    
    .pending-payment-card:hover {
        background: var(--background);
        box-shadow: var(--shadow-sm);
    }
    
    .patient-avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--primary-color) 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        flex-shrink: 0;
    }
    
    .patient-info {
        flex: 1;
        min-width: 0;
    }
    
    .patient-name {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    
    .patient-details {
        font-size: 12px;
        color: var(--text-secondary);
    }
    
    .payment-amount {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        text-align: right;
        margin-bottom: 4px;
    }
    
    .payment-method-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 500;
    }
    
    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }
    
    .quick-action-btn {
        flex: 1;
        min-width: 120px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    
    .quick-action-btn:hover {
        border-color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }
    
    .quick-action-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .quick-action-label {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 12px;
        text-align: center;
    }
    
    /* Recent Transactions */
    .transaction-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid var(--border-color);
        transition: background-color 0.2s ease;
    }
    
    .transaction-item:hover {
        background: var(--background);
    }
    
    .transaction-time {
        width: 80px;
        flex-shrink: 0;
    }
    
    .time-main {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    
    .time-sub {
        font-size: 11px;
        color: var(--text-secondary);
    }
    
    .transaction-patient {
        flex: 1;
        min-width: 0;
        padding: 0 16px;
    }
    
    .patient-name-sm {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 2px;
    }
    
    .patient-meta {
        font-size: 11px;
        color: var(--text-secondary);
    }
    
    .transaction-amount {
        font-weight: 600;
        color: var(--text-primary);
        text-align: right;
        width: 100px;
        flex-shrink: 0;
    }
    
    .transaction-status {
        width: 80px;
        flex-shrink: 0;
    }
    
    .transaction-actions {
        width: 80px;
        flex-shrink: 0;
        text-align: right;
    }
    
    /* Empty States */
    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }
    
    .empty-state-icon {
        font-size: 2.5rem;
        color: var(--border-color);
        margin-bottom: 16px;
        opacity: 0.5;
    }
    
    .empty-state-text {
        color: var(--text-secondary);
        margin: 0;
    }
    
    /* Chart Container */
    .chart-minimal {
        height: 200px;
        margin-bottom: 24px;
    }
    
    /* Summary Grid */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .summary-item {
        background: var(--background);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 12px;
    }
    
    .summary-label {
        font-size: 11px;
        color: var(--text-secondary);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    
    .summary-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    /* Status Colors */
    .status-paid {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    /* Method Colors */
    .method-cash {
        background-color: rgba(99, 102, 241, 0.1);
        color: var(--primary-color);
    }
    
    .method-transfer {
        background-color: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
    
    .method-qris {
        background-color: rgba(14, 165, 233, 0.1);
        color: #0284c7;
    }
    
    .method-ewallet {
        background-color: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }
    
    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .quick-action-btn {
            min-width: calc(50% - 6px);
        }
        
        .transaction-item {
            flex-wrap: wrap;
            padding: 12px;
        }
        
        .transaction-patient {
            order: 3;
            width: 100%;
            padding: 8px 0 0 0;
        }
        
        .transaction-amount {
            order: 2;
            text-align: left;
            width: auto;
            flex: 1;
        }
        
        .transaction-actions {
            order: 1;
            width: auto;
        }
        
        .summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-1">Dashboard Kasir</h1>
                <p class="page-subtitle">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
            <a href="{{ route('transactions.step1') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Transaksi Baru
            </a>
        </div>

        

        <!-- Stats Overview -->
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="cashier-stat-card">
                    <div class="stat-icon-circle" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Transaksi Hari Ini</div>
                        <div class="stat-value">{{ number_format($stats['todayTransactions']) }}</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="cashier-stat-card">
                    <div class="stat-icon-circle" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Pendapatan Hari Ini</div>
                        <div class="stat-value">
                            <span class="stat-currency">Rp</span> {{ number_format($stats['todayIncome'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- <div class="col-md-6 col-lg-3">
                <div class="cashier-stat-card">
                    <div class="stat-icon-circle" style="background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Menunggu Pembayaran</div>
                        <div class="stat-value">{{ number_format($stats['pendingTransactions']) }}</div>
                    </div>
                </div>
            </div> --}}
            
            <div class="col-md-6 col-lg-3">
                <div class="cashier-stat-card">
                    <div class="stat-icon-circle" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Rata-rata Transaksi</div>
                        <div class="stat-value">
                            <span class="stat-currency">Rp</span> {{ number_format($stats['avgTransaction'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="row g-3">
            <!-- Left Column: Pending Payments -->
            <!-- Left Column: Yearly Transactions Chart -->
<div class="col-lg-6">
    <div class="card h-100">
        <div class="card-header">
            <h5 class="mb-0">Transaksi Per Tahun</h5>
            <small class="text-muted">Ringkasan pendapatan tahunan</small>
        </div>
        <div class="card-body">
            <div class="chart-minimal" style="height:260px">
                <canvas id="yearlyTransactionChart"></canvas>
            </div>
        </div>
    </div>
</div>


            <!-- Right Column: Stats & Chart -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Statistik Hari Ini</h5>
                            <small class="text-muted">Ringkasan performa</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Chart -->
                        <div class="chart-minimal">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                        
                        <!-- Summary Grid -->
                        <div class="summary-grid mb-4">
                            <div class="summary-item">
                                <div class="summary-label">Transaksi</div>
                                <div class="summary-value">{{ $stats['todayTransactions'] }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Pendapatan</div>
                                <div class="summary-value text-success">Rp {{ number_format($stats['todayIncome'], 0, ',', '.') }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Rata-rata</div>
                                <div class="summary-value">Rp {{ number_format($stats['avgTransaction'], 0, ',', '.') }}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Pending</div>
                                <div class="summary-value">{{ $stats['pendingTransactions'] }}</div>
                            </div>
                        </div>
                        
                        <!-- Method Breakdown -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Metode Pembayaran</small>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge method-cash">TUNAI</span>
                                <span class="badge method-transfer">TRANSFER</span>
                                <span class="badge method-qris">QRIS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="row mt-3">
            <div class="col-12">
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
                        @if(count($stats['recentTransactions']) > 0)
                            @foreach($stats['recentTransactions'] as $transaction)
                            <div class="transaction-item">
                                <div class="transaction-time">
                                    <div class="time-main">{{ $transaction->created_at->format('H:i') }}</div>
                                    <div class="time-sub">{{ $transaction->created_at->format('d/m') }}</div>
                                </div>
                                <div class="transaction-patient">
                                    <div class="patient-name-sm">{{ $transaction->visit->patient->nama }}</div>
                                    <div class="patient-meta">{{ $transaction->visit->patient->no_rekam_medis }}</div>
                                </div>
                                <div class="transaction-amount">
                                    Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}
                                </div>
                                <div class="transaction-status">
                                    <span class="badge status-{{ $transaction->status == 'lunas' ? 'paid' : 'pending' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                                <div class="transaction-actions">
                                    <a href="{{ route('transactions.show', $transaction) }}" 
                                       class="btn btn-sm btn-outline-secondary" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye fa-xs"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="empty-state">
                                <i class="fas fa-inbox empty-state-icon"></i>
                                <p class="empty-state-text">Belum ada transaksi hari ini</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment method chart
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
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(14, 165, 233, 0.8)',
                        'rgba(245, 158, 11, 0.8)'
                    ],
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.parsed.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    }
                },
                cutout: '65%',
                spacing: 0
            }
        });
        
        // Auto refresh pending payments every 30 seconds
        setInterval(() => {
            fetch('{{ route("transactions.index") }}?status=menunggu&partial=1')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const pendingPayments = doc.querySelector('.pending-payments-list');
                    if (pendingPayments) {
                        document.querySelector('.card-body.p-0').innerHTML = pendingPayments.innerHTML;
                    }
                })
                .catch(error => console.error('Error refreshing pending payments:', error));
        }, 30000);
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* =========================
       YEARLY TRANSACTION CHART
    ========================== */
    new Chart(document.getElementById('yearlyTransactionChart'), {
        type: 'bar',
        data: {
            labels: @json($yearlyStats['years']),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: @json($yearlyStats['totals']),
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    /* =========================
       DAILY TRANSACTION CHART
    ========================== */
    new Chart(document.getElementById('dailyTransactionChart'), {
        type: 'line',
        data: {
            labels: @json($dailyStats['dates']),
            datasets: [{
                label: 'Pendapatan Harian',
                data: @json($dailyStats['totals']),
                borderColor: 'rgba(16, 185, 129, 1)',
                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                fill: true,
                tension: 0.4,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    ticks: {
                        callback: value => 'Rp ' + value.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

});
</script>

@endsection