@extends('reports.print-layout')

@section('content')
    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-value currency">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['transaction_count'] }}</div>
            <div class="stat-label">Jumlah Transaksi</div>
        </div>
        <div class="stat-card">
            <div class="stat-value currency">Rp {{ number_format($stats['average_daily'], 0, ',', '.') }}</div>
            <div class="stat-label">Rata-rata Harian</div>
        </div>
        <div class="stat-card">
            <div class="stat-value currency">Rp {{ number_format($stats['max_daily'], 0, ',', '.') }}</div>
            <div class="stat-label">Maksimal Harian</div>
        </div>
    </div>
    
    <!-- Summary -->
    <div class="summary-section">
        <div class="summary-title">Ringkasan Pendapatan</div>
        <p>Total pendapatan periode ini: <strong class="currency">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</strong></p>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li>Jumlah transaksi: {{ $stats['transaction_count'] }} transaksi</li>
            <li>Rata-rata transaksi: <span class="currency">Rp {{ number_format($stats['total_income'] / max($stats['transaction_count'], 1), 0, ',', '.') }}</span></li>
            <li>Rata-rata harian: <span class="currency">Rp {{ number_format($stats['average_daily'], 0, ',', '.') }}</span></li>
            <li>Pendapatan tertinggi harian: <span class="currency">Rp {{ number_format($stats['max_daily'], 0, ',', '.') }}</span></li>
            @php
                $daysCount = count($daily_income);
                $growthRate = $daysCount > 1 ? (($stats['total_income'] / $daysCount) / ($stats['total_income'] / ($daysCount - 1)) - 1) * 100 : 0;
            @endphp
            <li>Tren pertumbuhan: {{ number_format($growthRate, 1) }}%</li>
        </ul>
    </div>
    
    <!-- Daily Income -->
    <div class="mt-3">
        <div class="summary-title">Pendapatan Harian</div>
        <table class="table">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Tanggal</th>
                    <th width="120">Jumlah Transaksi</th>
                    <th width="150">Total Pendapatan</th>
                    <th width="150">Rata-rata per Transaksi</th>
                    <th width="100">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $dailyTransactions = $data->groupBy(function($t) {
                        return $t->created_at->format('Y-m-d');
                    });
                @endphp
                @foreach($daily_income as $date => $income)
                @php
                    $dayTransactions = $dailyTransactions[$date] ?? collect([]);
                    $transactionCount = $dayTransactions->count();
                    $averageTransaction = $transactionCount > 0 ? $income / $transactionCount : 0;
                    
                    // Determine day status based on income
                    $dayStatus = 'normal';
                    if ($income > $stats['average_daily'] * 1.5) {
                        $dayStatus = 'high';
                    } elseif ($income < $stats['average_daily'] * 0.5) {
                        $dayStatus = 'low';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ date('d/m/Y', strtotime($date)) }}</td>
                    <td class="text-center">{{ $transactionCount }}</td>
                    <td class="text-right currency">Rp {{ number_format($income, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($dayStatus == 'high')
                            <span class="badge badge-success">Tinggi</span>
                        @elseif($dayStatus == 'low')
                            <span class="badge badge-warning">Rendah</span>
                        @else
                            <span class="badge badge-info">Normal</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            @if(count($daily_income) > 0)
            <tfoot>
                <tr style="background: #f1f5f9; font-weight: 600;">
                    <td colspan="3" class="text-right">Total:</td>
                    <td class="text-right currency">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($stats['total_income'] / $stats['transaction_count'], 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    
    <!-- Monthly Comparison -->
    <div class="page-break mt-3">
        <div class="summary-section">
            <div class="summary-title">Perbandingan Bulanan</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th width="150">Total Pendapatan</th>
                        <th width="120">Transaksi</th>
                        <th width="150">Rata-rata Transaksi</th>
                        <th width="100">Tren</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $previousMonthIncome = null;
                    @endphp
                    @foreach($monthly_income as $month => $income)
                    @php
                        $monthTransactions = $data->filter(function($t) use ($month) {
                            return $t->created_at->format('Y-m') == $month;
                        });
                        $transactionCount = $monthTransactions->count();
                        $averageTransaction = $transactionCount > 0 ? $income / $transactionCount : 0;
                        
                        // Calculate trend
                        $trend = '';
                        if ($previousMonthIncome !== null) {
                            $growth = $previousMonthIncome > 0 ? (($income - $previousMonthIncome) / $previousMonthIncome) * 100 : 100;
                            if ($growth > 10) {
                                $trend = 'up';
                            } elseif ($growth < -10) {
                                $trend = 'down';
                            } else {
                                $trend = 'stable';
                            }
                        }
                        $previousMonthIncome = $income;
                    @endphp
                    <tr>
                        <td>{{ date('F Y', strtotime($month . '-01')) }}</td>
                        <td class="text-right currency">Rp {{ number_format($income, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $transactionCount }}</td>
                        <td class="text-right currency">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($trend == 'up')
                                <span class="badge badge-success">↑ Naik</span>
                            @elseif($trend == 'down')
                                <span class="badge badge-danger">↓ Turun</span>
                            @elseif($trend == 'stable')
                                <span class="badge badge-info">→ Stabil</span>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Payment Method Analysis -->
        <div class="summary-section mt-3">
            <div class="summary-title">Analisis Metode Pembayaran</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <th width="100">Transaksi</th>
                        <th width="150">Total Pendapatan</th>
                        <th width="100">Persentase</th>
                        <th width="150">Rata-rata Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $paymentMethods = $data->groupBy('metode_pembayaran');
                    @endphp
                    @foreach($paymentMethods as $method => $transactions)
                    <tr>
                        <td>{{ ucfirst($method) }}</td>
                        <td class="text-center">{{ $transactions->count() }}</td>
                        <td class="text-right currency">Rp {{ number_format($transactions->sum('total_biaya'), 0, ',', '.') }}</td>
                        <td class="text-center">{{ $stats['total_income'] > 0 ? number_format(($transactions->sum('total_biaya') / $stats['total_income']) * 100, 1) : 0 }}%</td>
                        <td class="text-right currency">Rp {{ number_format($transactions->avg('total_biaya'), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Top Transactions -->
        <div class="summary-section mt-3">
            <div class="summary-title">10 Transaksi Tertinggi</div>
            <table class="table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th width="100">Tanggal</th>
                        <th>Pasien</th>
                        <th width="100">Metode</th>
                        <th width="150">Total</th>
                        <th width="100">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->sortByDesc('total_biaya')->take(10) as $transaction)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                        <td>{{ $transaction->visit->patient->nama }}</td>
                        <td class="text-center">
                            <span class="badge badge-{{ $transaction->metode_pembayaran == 'tunai' ? 'primary' : 'success' }}">
                                {{ ucfirst($transaction->metode_pembayaran) }}
                            </span>
                        </td>
                        <td class="text-right currency">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="badge badge-success">Lunas</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Income Projection -->
    <div class="page-break mt-3">
        <div class="summary-section">
            <div class="summary-title">Proyeksi Pendapatan</div>
            <p>Berdasarkan data periode ini:</p>
            
            @php
                $daysCount = count($daily_income);
                $monthsCount = count($monthly_income);
                
                // Calculate projections
                $projectedMonthly = $daysCount > 0 ? ($stats['total_income'] / $daysCount) * 30 : 0;
                $projectedQuarterly = $projectedMonthly * 3;
                $projectedYearly = $projectedMonthly * 12;
                
                // Calculate growth if we have monthly data
                $growthRate = 0;
                if ($monthsCount >= 2) {
                    $months = array_values($monthly_income);
                    $lastMonth = end($months);
                    $firstMonth = reset($months);
                    $growthRate = $firstMonth > 0 ? (($lastMonth - $firstMonth) / $firstMonth) * 100 : 100;
                }
            @endphp
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th width="200">Proyeksi Pendapatan</th>
                        <th width="150">Estimasi Transaksi</th>
                        <th width="150">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Bulanan</td>
                        <td class="text-right currency">Rp {{ number_format($projectedMonthly, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $daysCount > 0 ? round(($stats['transaction_count'] / $daysCount) * 30) : 0 }}</td>
                        <td>Proyeksi bulan berikutnya</td>
                    </tr>
                    <tr>
                        <td>Triwulan</td>
                        <td class="text-right currency">Rp {{ number_format($projectedQuarterly, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $daysCount > 0 ? round(($stats['transaction_count'] / $daysCount) * 90) : 0 }}</td>
                        <td>Proyeksi 3 bulan ke depan</td>
                    </tr>
                    <tr>
                        <td>Tahunan</td>
                        <td class="text-right currency">Rp {{ number_format($projectedYearly, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $daysCount > 0 ? round(($stats['transaction_count'] / $daysCount) * 365) : 0 }}</td>
                        <td>Proyeksi 1 tahun ke depan</td>
                    </tr>
                </tbody>
            </table>
            
            @if($growthRate != 0)
            <div style="margin-top: 15px; padding: 10px; background: #f1f5f9; border-radius: 6px;">
                <strong>Tren Pertumbuhan: {{ number_format($growthRate, 1) }}%</strong>
                <p style="margin: 5px 0 0 0; font-size: 10px;">
                    @if($growthRate > 0)
                    Pendapatan menunjukkan tren positif dengan pertumbuhan {{ number_format($growthRate, 1) }}%.
                    @else
                    Pendapatan menunjukkan penurunan sebesar {{ number_format(abs($growthRate), 1) }}% dan memerlukan evaluasi.
                    @endif
                </p>
            </div>
            @endif
        </div>
        
        <!-- Recommendations -->
        <div class="summary-section mt-3">
            <div class="summary-title">Rekomendasi</div>
            <ul style="margin: 0; padding-left: 20px;">
                @if($stats['average_daily'] < 1000000)
                <li>Pertimbangkan untuk meningkatkan promosi atau layanan untuk meningkatkan pendapatan harian</li>
                @endif
                
                @php
                    $cashPercentage = $paymentMethods['tunai']->sum('total_biaya') / max($stats['total_income'], 1) * 100;
                @endphp
                
                @if($cashPercentage > 80)
                <li>Diversifikasi metode pembayaran non-tunai untuk meningkatkan kemudahan transaksi</li>
                @endif
                
                @if($projectedMonthly > $stats['total_income'])
                <li>Pertahankan tren positif untuk mencapai proyeksi bulanan</li>
                @else
                <li>Evaluasi strategi untuk mencapai target proyeksi pendapatan</li>
                @endif
                
                @if(count($daily_income) < 10)
                <li>Pertimbangkan untuk memperpanjang periode analisis untuk data yang lebih akurat</li>
                @endif
            </ul>
        </div>
    </div>
@endsection