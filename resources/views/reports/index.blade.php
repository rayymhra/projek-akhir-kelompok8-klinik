@extends('layouts.app')

@section('title', 'Laporan Sistem')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Laporan Sistem
                </h5>
            </div>
            <div class="card-body">
                <!-- Report Type Selector -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="btn-group w-100" role="group">
                            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'visits'])) }}" 
                               class="btn {{ ($filter['type'] ?? 'visits') == 'visits' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-calendar-check me-2"></i>Kunjungan
                            </a>
                            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'transactions'])) }}" 
                               class="btn {{ ($filter['type'] ?? '') == 'transactions' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-money-bill-wave me-2"></i>Transaksi
                            </a>
                            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'patients'])) }}" 
                               class="btn {{ ($filter['type'] ?? '') == 'patients' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-user-injured me-2"></i>Pasien
                            </a>
                            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'medicines'])) }}" 
                               class="btn {{ ($filter['type'] ?? '') == 'medicines' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-pills me-2"></i>Obat
                            </a>
                            <a href="{{ route('reports.index', array_merge($filter, ['type' => 'income'])) }}" 
                               class="btn {{ ($filter['type'] ?? '') == 'income' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-chart-line me-2"></i>Pendapatan
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" class="row g-3">
                            <input type="hidden" name="type" value="{{ $filter['type'] ?? 'visits' }}">
                            
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" name="start_date" 
                                       value="{{ $filter['start_date'] }}" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" name="end_date" 
                                       value="{{ $filter['end_date'] }}" required>
                            </div>
                            
                            @if(($filter['type'] ?? 'visits') == 'visits')
                            <div class="col-md-3">
                                <label class="form-label">Dokter</label>
                                <select name="doctor_id" class="form-select">
                                    <option value="">Semua Dokter</option>
                                    @foreach(\App\Models\User::where('role', 'dokter')->get() as $doctor)
                                        <option value="{{ $doctor->id }}" 
                                                {{ ($filter['doctor_id'] ?? '') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                    <a href="{{ route('reports.index', ['type' => $filter['type'] ?? 'visits']) }}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-redo me-2"></i>Reset
                                    </a>
                                    <a href="{{ route('reports.export', [
    'type' => $filter['type'] ?? 'visits',
    'start_date' => $filter['start_date'] ?? null,
    'end_date' => $filter['end_date'] ?? null,
]) }}" class="btn btn-success" target="_blank">

                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Content -->
                @php
                    $reportType = $reports['type'] ?? 'visits';
                @endphp
                
                @if($reportType == 'visits')
                <!-- Visit Report -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Statistik Kunjungan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Total Kunjungan</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['total'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Menunggu</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['menunggu'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Diperiksa</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['diperiksa'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Selesai</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['selesai'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Detail Kunjungan</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Pasien</th>
                                                <th>Dokter</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($reports['data'] as $visit)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $visit->tanggal_kunjungan->format('d/m/Y') }}</td>
                                                <td>{{ $visit->patient->nama }}</td>
                                                <td>{{ $visit->doctor->name }}</td>
                                                <td>
                                                    <span class="badge badge-status-{{ $visit->status }}">
                                                        {{ ucfirst($visit->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Tidak ada data kunjungan</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Distribusi Harian</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="dailyVisitChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($reportType == 'transactions')
                <!-- Transaction Report -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Statistik Transaksi</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Total Transaksi</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['total'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Total Pendapatan</h6>
                                                <h3 class="mb-0">Rp {{ number_format($reports['stats']['total_income'] ?? 0, 0, ',', '.') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Lunas</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['lunas'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Menunggu</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['menunggu'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Detail Transaksi</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Pasien</th>
                                                <th>Total</th>
                                                <th>Metode</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($reports['data'] as $transaction)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $transaction->visit->patient->nama }}</td>
                                                <td>Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</td>
                                                <td>
                                                    @switch($transaction->metode_pembayaran)
                                                        @case('tunai')
                                                            <span class="badge bg-primary">Tunai</span>
                                                            @break
                                                        @case('transfer')
                                                            <span class="badge bg-success">Transfer</span>
                                                            @break
                                                        @case('qris')
                                                            <span class="badge bg-info">QRIS</span>
                                                            @break
                                                        @case('e-wallet')
                                                            <span class="badge bg-warning">E-Wallet</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <span class="badge badge-status-{{ $transaction->status }}">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data transaksi</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Metode Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="paymentMethodChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($reportType == 'patients')
                <!-- Patient Report -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Statistik Pasien</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Total Pasien</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['total'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Laki-laki</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['male'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Perempuan</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['female'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Pasien Baru</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['new_patients'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Daftar Pasien</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>No. RM</th>
                                                <th>Nama</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Umur</th>
                                                <th>Total Kunjungan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($reports['data'] as $patient)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $patient->no_rekam_medis }}</td>
                                                <td>{{ $patient->nama }}</td>
                                                <td>{{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                <td>{{ $patient->umur }} tahun</td>
                                                <td>{{ $patient->visits_count }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data pasien</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Distribusi Usia</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="ageDistributionChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($reportType == 'income')
                <!-- Income Report -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Statistik Pendapatan</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="card bg-success text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Total Pendapatan</h6>
                                                <h3 class="mb-0">Rp {{ number_format($reports['stats']['total_income'] ?? 0, 0, ',', '.') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-info text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Rata-rata Harian</h6>
                                                <h3 class="mb-0">Rp {{ number_format($reports['stats']['average_daily'] ?? 0, 0, ',', '.') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Maksimal Harian</h6>
                                                <h3 class="mb-0">Rp {{ number_format($reports['stats']['max_daily'] ?? 0, 0, ',', '.') }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body py-3">
                                                <h6 class="mb-0">Jumlah Transaksi</h6>
                                                <h3 class="mb-0">{{ $reports['stats']['transaction_count'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Tren Pendapatan Harian</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="incomeTrendChart" height="100"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Pendapatan Bulanan</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    @forelse($reports['monthly_income'] ?? [] as $month => $income)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ date('F Y', strtotime($month . '-01')) }}
                                        <span class="badge bg-success rounded-pill">
                                            Rp {{ number_format($income, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    @empty
                                    <div class="list-group-item text-center">
                                        Tidak ada data pendapatan
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .btn-group .btn {
        border-radius: 0;
    }
    
    .btn-group .btn:first-child {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }
    
    .btn-group .btn:last-child {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportType = '{{ $reports["type"] ?? "visits" }}';
        
        if (reportType === 'visits') {
            renderVisitChart();
        } else if (reportType === 'transactions') {
            renderPaymentMethodChart();
        } else if (reportType === 'patients') {
            renderAgeDistributionChart();
        } else if (reportType === 'income') {
            renderIncomeTrendChart();
        }
        
        function renderVisitChart() {
            const ctx = document.getElementById('dailyVisitChart').getContext('2d');
            const data = @json($reports['grouped'] ?? []);
            
            const labels = Object.keys(data).map(date => {
                return new Date(date).toLocaleDateString('id-ID', { 
                    day: 'numeric',
                    month: 'short'
                });
            });
            
            const datasets = [{
                label: 'Total Kunjungan',
                data: Object.values(data).map(d => d.total),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }];
            
            new Chart(ctx, {
                type: 'bar',
                data: { labels, datasets },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }
        
        function renderPaymentMethodChart() {
            const ctx = document.getElementById('paymentMethodChart').getContext('2d');
            const data = @json($reports['payment_methods'] ?? []);


            
            const labels = Object.keys(data).map(method => {
                const methodNames = {
                    'tunai': 'Tunai',
                    'transfer': 'Transfer',
                    'qris': 'QRIS',
                    'e-wallet': 'E-Wallet'
                };
                return methodNames[method] || method;
            });
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(153, 102, 255, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        function renderAgeDistributionChart() {
            const ctx = document.getElementById('ageDistributionChart').getContext('2d');
            const data = @json($reports['age_groups'] ?? []);
            
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 206, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(153, 102, 255, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
        
        function renderIncomeTrendChart() {
            const ctx = document.getElementById('incomeTrendChart').getContext('2d');
            const data = @json($reports['daily_income'] ?? []);
            
            const labels = Object.keys(data).map(date => {
                return new Date(date).toLocaleDateString('id-ID', { 
                    day: 'numeric',
                    month: 'short'
                });
            });
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pendapatan Harian',
                        data: Object.values(data),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection