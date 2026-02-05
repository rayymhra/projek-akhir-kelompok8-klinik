@extends('layouts.app')

@section('title', 'Obat Akan Kadaluwarsa')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-times text-danger me-2"></i>Obat Akan Kadaluwarsa
        </h1>
        <div>
            <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Obat
            </a>
            <a href="{{ route('medicines.low-stock') }}" class="btn btn-warning ms-2">
                <i class="fas fa-exclamation-triangle me-2"></i>Lihat Stok Rendah
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Obat Akan Kadaluwarsa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiredSoonMedicines->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-danger"></i>
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
                                Kadaluwarsa ≤ 7 Hari
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $sevenDays = now()->addDays(7);
                                    $count7Days = $expiredSoonMedicines->filter(function($medicine) use ($sevenDays) {
                                        return $medicine->expired_date && $medicine->expired_date <= $sevenDays;
                                    })->count();
                                @endphp
                                {{ $count7Days }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
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
                                Kadaluwarsa ≤ 14 Hari
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $fourteenDays = now()->addDays(14);
                                    $count14Days = $expiredSoonMedicines->filter(function($medicine) use ($fourteenDays) {
                                        return $medicine->expired_date && $medicine->expired_date <= $fourteenDays;
                                    })->count();
                                @endphp
                                {{ $count14Days }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Nilai Obat
                            </div>
                            @php
                                $totalValue = $expiredSoonMedicines->sum(function($medicine) {
                                    return $medicine->stok * $medicine->harga;
                                });
                            @endphp
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalValue, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    @if($expiredSoonMedicines->count() > 0)
    <div class="alert alert-danger">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading">Peringatan Kadaluwarsa!</h5>
                <p class="mb-0">
                    Terdapat <strong>{{ $expiredSoonMedicines->count() }}</strong> obat yang akan kadaluwarsa dalam 30 hari ke depan.
                    Segera lakukan pengecekan dan penanganan untuk menghindari kerugian.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Medicines Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-pills me-2"></i>Daftar Obat Akan Kadaluwarsa
            </h6>
            <div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="printReport()">
                    <i class="fas fa-print me-1"></i>Cetak Laporan
                </button>
                <button type="button" class="btn btn-outline-success btn-sm ms-2" onclick="exportToExcel()">
                    <i class="fas fa-file-excel me-1"></i>Export Excel
                </button>
                <button type="button" class="btn btn-outline-warning btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#reminderModal">
                    <i class="fas fa-bell me-1"></i>Atur Pengingat
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($expiredSoonMedicines->count() > 0)
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filterStatus" onchange="filterTable()">
                            <option value="all">Semua Status</option>
                            <option value="critical">Kritis (≤7 hari)</option>
                            <option value="warning">Peringatan (8-14 hari)</option>
                            <option value="notice">Perhatian (15-30 hari)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filterStock" onchange="filterTable()">
                            <option value="all">Semua Stok</option>
                            <option value="high">Stok Tinggi (>10)</option>
                            <option value="medium">Stok Sedang (1-10)</option>
                            <option value="low">Stok Rendah (0)</option>
                        </select>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-info" onclick="sortBy('days_left')">
                                <i class="fas fa-sort-numeric-down me-1"></i>Urutkan Sisa Hari
                            </button>
                            <button type="button" class="btn btn-outline-info ms-1" onclick="sortBy('expired_date')">
                                <i class="fas fa-sort-calendar me-1"></i>Urutkan Tanggal
                            </button>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="expiryTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Obat</th>
                                <th>Nama Obat</th>
                                <th width="10%">Batch</th>
                                <th width="15%">Tanggal Kadaluwarsa</th>
                                <th width="12%">Sisa Hari</th>
                                <th width="10%">Stok</th>
                                <th width="13%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expiredSoonMedicines as $index => $medicine)
                                @php
                                    $today = now();
                                    $expiryDate = $medicine->expired_date;
                                    $daysLeft = $today->diffInDays($expiryDate, false);
                                    
                                    $statusClass = '';
                                    $statusText = '';
                                    $progressColor = '';
                                    $progressWidth = 0;
                                    
                                    if ($daysLeft < 0) {
                                        $statusClass = 'dark';
                                        $statusText = 'SUDAH KADALUWARSA';
                                        $progressColor = '#6c757d';
                                        $progressWidth = 100;
                                    } elseif ($daysLeft <= 7) {
                                        $statusClass = 'danger';
                                        $statusText = 'KRITIS';
                                        $progressColor = '#dc3545';
                                        $progressWidth = 100 - (($daysLeft / 7) * 100);
                                    } elseif ($daysLeft <= 14) {
                                        $statusClass = 'warning';
                                        $statusText = 'PERINGATAN';
                                        $progressColor = '#ffc107';
                                        $progressWidth = 100 - (($daysLeft / 14) * 100);
                                    } elseif ($daysLeft <= 30) {
                                        $statusClass = 'info';
                                        $statusText = 'PERHATIAN';
                                        $progressColor = '#0dcaf0';
                                        $progressWidth = 100 - (($daysLeft / 30) * 100);
                                    }
                                    
                                    $stockValue = $medicine->stok * $medicine->harga;
                                @endphp
                                <tr class="expiry-row" 
                                    data-status="{{ $daysLeft <= 7 ? 'critical' : ($daysLeft <= 14 ? 'warning' : 'notice') }}"
                                    data-stock="{{ $medicine->stok > 10 ? 'high' : ($medicine->stok > 0 ? 'medium' : 'low') }}"
                                    data-days="{{ $daysLeft }}"
                                    data-date="{{ $expiryDate->format('Y-m-d') }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $medicine->kode_obat }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $medicine->nama_obat }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $medicine->jenis_obat }} • {{ $medicine->satuan ?? 'Tablet' }}</small>
                                    </td>
                                    <td>
                                        @if($medicine->batch_number)
                                            <span class="badge bg-light text-dark">{{ $medicine->batch_number }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $expiryDate->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $expiryDate->isoFormat('dddd') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($daysLeft < 0)
                                                <span class="badge bg-dark me-2">{{ abs($daysLeft) }}</span>
                                                <span>Hari Terlambat</span>
                                            @else
                                                <span class="badge bg-{{ $statusClass }} me-2">{{ $daysLeft }}</span>
                                                <span>Hari Lagi</span>
                                            @endif
                                        </div>
                                        <div class="progress mt-1" style="height: 5px;">
                                            <div class="progress-bar" 
                                                 style="width: {{ $progressWidth }}%; background-color: {{ $progressColor }};">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $medicine->stok }} {{ $medicine->satuan ?? 'Tablet' }}
                                        <br>
                                        <small class="text-primary">
                                            Rp {{ number_format($stockValue, 0, ',', '.') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusClass }}">
                                            <i class="fas fa-{{ $daysLeft <= 7 ? 'exclamation-circle' : ($daysLeft <= 14 ? 'exclamation-triangle' : 'info-circle') }} me-1"></i>
                                            {{ $statusText }}
                                        </span>
                                        @if($medicine->stok == 0)
                                            <br>
                                            <span class="badge bg-secondary mt-1">STOK HABIS</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('medicines.edit', $medicine) }}" 
                                               class="btn btn-warning" 
                                               title="Edit Obat">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" 
                                                    onclick="markAsDiscarded({{ $medicine->id }})"
                                                    title="Tandai Dibuang">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button type="button" class="btn btn-info" 
                                                    onclick="showMedicineDetails({{ $medicine->id }})"
                                                    title="Detail Obat">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary Cards -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card border-left-danger">
                            <div class="card-body">
                                <h6 class="card-title text-danger">
                                    <i class="fas fa-fire me-2"></i>Obat Kritis
                                </h6>
                                @php
                                    $criticalMeds = $expiredSoonMedicines->filter(function($medicine) {
                                        $daysLeft = now()->diffInDays($medicine->expired_date, false);
                                        return $daysLeft <= 7;
                                    });
                                @endphp
                                <h2 class="display-6">{{ $criticalMeds->count() }}</h2>
                                <p class="card-text">Obat akan kadaluwarsa dalam 7 hari</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-warning">
                            <div class="card-body">
                                <h6 class="card-title text-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Obat Peringatan
                                </h6>
                                @php
                                    $warningMeds = $expiredSoonMedicines->filter(function($medicine) {
                                        $daysLeft = now()->diffInDays($medicine->expired_date, false);
                                        return $daysLeft > 7 && $daysLeft <= 14;
                                    });
                                @endphp
                                <h2 class="display-6">{{ $warningMeds->count() }}</h2>
                                <p class="card-text">Obat akan kadaluwarsa dalam 8-14 hari</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-left-info">
                            <div class="card-body">
                                <h6 class="card-title text-info">
                                    <i class="fas fa-info-circle me-2"></i>Total Nilai Risiko
                                </h6>
                                @php
                                    $totalRiskValue = $expiredSoonMedicines->sum(function($medicine) {
                                        return $medicine->stok * $medicine->harga;
                                    });
                                @endphp
                                <h2 class="display-6">Rp {{ number_format($totalRiskValue, 0, ',', '.') }}</h2>
                                <p class="card-text">Total nilai obat yang berisiko</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Panel -->
                <div class="card mt-4 border-left-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-tasks me-2"></i>Rekomendasi Tindakan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Untuk Obat Stok Tinggi:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Prioritaskan penggunaan dalam resep
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Pertimbangkan transfer ke unit lain
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Berikan diskon atau promosi
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Untuk Obat Stok Rendah:</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Gunakan segera untuk pasien yang membutuhkan
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Batasi pengeluaran dari gudang
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        Siapkan untuk dimusnahkan jika perlu
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-calendar-check fa-4x text-success"></i>
                    </div>
                    <h4 class="text-success">Tidak Ada Obat Akan Kadaluwarsa</h4>
                    <p class="text-muted">Tidak ada obat yang akan kadaluwarsa dalam 30 hari ke depan.</p>
                    <div class="mt-4">
                        <a href="{{ route('medicines.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>Lihat Semua Obat
                        </a>
                        <a href="{{ route('medicines.create') }}" class="btn btn-success ms-2">
                            <i class="fas fa-plus me-2"></i>Tambah Obat Baru
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reminder Modal -->
<div class="modal fade" id="reminderModal" tabindex="-1" aria-labelledby="reminderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reminderModalLabel">
                    <i class="fas fa-bell me-2"></i>Atur Pengingat Kadaluwarsa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Pengingat Sebelum Kadaluwarsa:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="reminder7Days" checked>
                        <label class="form-check-label" for="reminder7Days">
                            7 Hari Sebelum
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="reminder14Days" checked>
                        <label class="form-check-label" for="reminder14Days">
                            14 Hari Sebelum
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="reminder30Days">
                        <label class="form-check-label" for="reminder30Days">
                            30 Hari Sebelum
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notifikasi Untuk:</label>
                    <select class="form-select" id="notificationTo">
                        <option value="pharmacist">Apoteker</option>
                        <option value="manager">Manajer</option>
                        <option value="both">Keduanya</option>
                        <option value="all">Semua Staf</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Frekuensi Pengingat:</label>
                    <select class="form-select" id="reminderFrequency">
                        <option value="daily">Setiap Hari</option>
                        <option value="weekly" selected>Setiap Minggu</option>
                        <option value="biweekly">Dua Kali Seminggu</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveReminderSettings()">
                    <i class="fas fa-save me-2"></i>Simpan Pengaturan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Detail Obat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="medicineDetails">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .progress {
        background-color: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .expiry-row.critical {
        background-color: rgba(220, 53, 69, 0.05) !important;
    }
    
    .expiry-row.warning {
        background-color: rgba(255, 193, 7, 0.05) !important;
    }
    
    .expiry-row.notice {
        background-color: rgba(13, 202, 240, 0.05) !important;
    }
    
    .table-hover tbody tr:hover {
        transform: translateY(-2px);
        transition: transform 0.2s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    
    .display-6 {
        font-size: 2rem;
        font-weight: 300;
        line-height: 1.2;
    }
</style>
@endsection

@section('scripts')
<script>
    function printReport() {
        window.print();
    }
    
    function exportToExcel() {
        // Simulate Excel export
        alert('Data akan diexport ke format Excel...\n\nIn a real application, this would generate an Excel file.');
    }
    
    function filterTable() {
        const statusFilter = document.getElementById('filterStatus').value;
        const stockFilter = document.getElementById('filterStock').value;
        
        document.querySelectorAll('.expiry-row').forEach(row => {
            const status = row.getAttribute('data-status');
            const stock = row.getAttribute('data-stock');
            
            let show = true;
            
            if (statusFilter !== 'all' && status !== statusFilter) {
                show = false;
            }
            
            if (stockFilter !== 'all' && stock !== stockFilter) {
                show = false;
            }
            
            row.style.display = show ? '' : 'none';
        });
    }
    
    function sortBy(criteria) {
        const rows = Array.from(document.querySelectorAll('.expiry-row'));
        
        rows.sort((a, b) => {
            if (criteria === 'days_left') {
                const daysA = parseInt(a.getAttribute('data-days'));
                const daysB = parseInt(b.getAttribute('data-days'));
                return daysA - daysB;
            } else if (criteria === 'expired_date') {
                const dateA = new Date(a.getAttribute('data-date'));
                const dateB = new Date(b.getAttribute('data-date'));
                return dateA - dateB;
            }
            return 0;
        });
        
        const tbody = document.querySelector('#expiryTable tbody');
        rows.forEach(row => tbody.appendChild(row));
        
        // Update row numbers
        document.querySelectorAll('.expiry-row').forEach((row, index) => {
            row.querySelector('td:first-child').textContent = index + 1;
        });
    }
    
    function markAsDiscarded(medicineId) {
        if (confirm('Apakah Anda yakin ingin menandai obat ini sebagai dibuang?\n\nIni akan menghapus obat dari sistem dan mencatatnya sebagai kerugian.')) {
            // Simulate API call
            alert(`Obat dengan ID ${medicineId} ditandai sebagai dibuang.\n\nIn a real application, this would send a request to the server.`);
        }
    }
    
    function showMedicineDetails(medicineId) {
        // Simulate loading medicine details
        const details = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        document.getElementById('medicineDetails').innerHTML = details;
        $('#detailsModal').modal('show');
        
        // Simulate API delay
        setTimeout(() => {
            const fakeDetails = `
                <h6>Informasi Lengkap Obat</h6>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nama Obat:</strong> Paracetamol 500mg</p>
                        <p><strong>Kode:</strong> MED-001</p>
                        <p><strong>Jenis:</strong> Tablet</p>
                        <p><strong>Satuan:</strong> Tablet</p>
                        <p><strong>Batch:</strong> BATCH-2024-001</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Stok:</strong> 50 Tablet</p>
                        <p><strong>Harga:</strong> Rp 1.500</p>
                        <p><strong>Nilai Stok:</strong> Rp 75.000</p>
                        <p><strong>Supplier:</strong> PT. Farmasi Sejahtera</p>
                        <p><strong>Lokasi:</strong> Rak A</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <p><strong>Kadaluwarsa:</strong> 15 Desember 2024 (12 hari lagi)</p>
                        <p><strong>Status:</strong> <span class="badge bg-warning">PERINGATAN</span></p>
                        <p><strong>Riwayat Terakhir:</strong> Update terakhir 5 hari yang lalu</p>
                    </div>
                </div>
            `;
            document.getElementById('medicineDetails').innerHTML = fakeDetails;
        }, 500);
    }
    
    function generateActionPlan() {
        alert('Membuat rencana tindakan untuk obat kadaluwarsa...\n\nIn a real application, this would generate a PDF action plan.');
    }
    
    function sendReminderToManager() {
        const criticalCount = document.querySelectorAll('.expiry-row[data-status="critical"]').length;
        
        if (confirm(`Kirim laporan kadaluwarsa ke manajer?\n\n${criticalCount} obat dalam kondisi kritis akan disertakan.`)) {
            alert('Laporan telah dikirim ke manajer melalui email.');
        }
    }
    
    function saveReminderSettings() {
        const reminder7Days = document.getElementById('reminder7Days').checked;
        const reminder14Days = document.getElementById('reminder14Days').checked;
        const reminder30Days = document.getElementById('reminder30Days').checked;
        const notificationTo = document.getElementById('notificationTo').value;
        const frequency = document.getElementById('reminderFrequency').value;
        
        alert('Pengaturan pengingat berhasil disimpan!');
        $('#reminderModal').modal('hide');
    }
    
    // Auto-refresh every 10 minutes
    setInterval(function() {
        if (!document.hidden) {
            const shouldRefresh = confirm('Halaman sudah lama tidak diperbarui. Segarkan halaman?');
            if (shouldRefresh) {
                location.reload();
            }
        }
    }, 600000); // 10 minutes
    
    // Initialize table sorting
    document.addEventListener('DOMContentLoaded', function() {
        sortBy('days_left');
    });
</script>

<!-- Print Styles -->
<style media="print">
    @media print {
        body * {
            visibility: hidden;
        }
        .card, .card * {
            visibility: visible;
        }
        .card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
        }
        .no-print, .btn-group, .modal, .alert {
            display: none !important;
        }
        .progress-bar {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        h1, h2, h3, h4, h5, h6 {
            page-break-after: avoid;
        }
        table {
            page-break-inside: auto;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .badge {
            border: 1px solid #000;
        }
    }

    /* Custom styles for expired-soon page */
.expiry-status-badge {
    font-size: 0.8em;
    padding: 0.3em 0.6em;
    border-radius: 10px;
}

.progress-bar-expiry {
    height: 8px;
    border-radius: 4px;
}

.table-expiry {
    font-size: 0.9rem;
}

.table-expiry th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.expiry-row-highlight {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        background-color: rgba(220, 53, 69, 0.05);
    }
    50% {
        background-color: rgba(220, 53, 69, 0.15);
    }
    100% {
        background-color: rgba(220, 53, 69, 0.05);
    }
}

/* Print optimizations */
@media print {
    .expiry-row {
        break-inside: avoid;
    }
    
    .badge {
        border: 1px solid #000 !important;
        color: #000 !important;
        background-color: transparent !important;
    }
}
</style>
@endsection