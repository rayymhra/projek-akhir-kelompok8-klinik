@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="h3 mb-3">Dashboard Admin</h1>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Total Pasien</h5>
                        <h2 class="mb-0">{{ $totalPatients }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-injured fa-3x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Kunjungan Hari Ini</h5>
                        <h2 class="mb-0">{{ $todayVisits }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-check fa-3x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Pendapatan Bulan Ini</h5>
                        <h2 class="mb-0">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-3x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Stok Obat Rendah</h5>
                        <h2 class="mb-0">{{ $lowStockMedicines }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-pills fa-3x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Kunjungan Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentVisits as $visit)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $visit->patient->nama }}</td>
                                <td>{{ $visit->doctor->name }}</td>
                                <td>{{ $visit->tanggal_kunjungan->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge badge-status-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Statistik Pengguna</h5>
            </div>
            <div class="card-body">
                <canvas id="userStatsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('patients.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Tambah Pasien
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('visits.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-calendar-plus me-2"></i>Daftarkan Kunjungan
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('medicines.create') }}" class="btn btn-warning w-100">
                            <i class="fas fa-pills me-2"></i>Tambah Obat
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('reports.index') }}" class="btn btn-info w-100">
                            <i class="fas fa-chart-bar me-2"></i>Lihat Laporan
                        </a>
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
    // User Statistics Chart
    const ctx = document.getElementById('userStatsChart').getContext('2d');
    const userStatsChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Petugas', 'Dokter', 'Kasir'],
            datasets: [{
                data: [
                    {{ $userStats['admin'] }},
                    {{ $userStats['petugas'] }},
                    {{ $userStats['dokter'] }},
                    {{ $userStats['kasir'] }}
                ],
                backgroundColor: [
                    '#2c3e50',
                    '#3498db',
                    '#2ecc71',
                    '#e74c3c'
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
</script>
@endsection