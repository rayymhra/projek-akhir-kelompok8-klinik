@extends('layouts.app')

@section('title', 'Obat Stok Rendah')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-exclamation-triangle text-warning me-2"></i>Obat Stok Rendah
        </h1>
        <div>
            <a href="{{ route('medicines.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Obat
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
                                Total Obat Stok Rendah
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockMedicines->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
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
                                Stok Hampir Habis (≤5)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $lowStockMedicines->where('stok', '<=', 5)->count() }}
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
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Stok Habis (0)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $lowStockMedicines->where('stok', 0)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-dark"></i>
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
                                Rata-rata Stok Tersisa
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($lowStockMedicines->count() > 0)
                                    {{ number_format($lowStockMedicines->avg('stok'), 1) }}
                                @else
                                    0
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert -->
    @if($lowStockMedicines->count() > 0)
    <div class="alert alert-warning">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
            <div>
                <h5 class="alert-heading">Perhatian!</h5>
                <p class="mb-0">
                    Terdapat <strong>{{ $lowStockMedicines->count() }}</strong> obat dengan stok rendah atau habis. 
                    Segera lakukan restock untuk menjaga ketersediaan obat.
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Medicines Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-pills me-2"></i>Daftar Obat Stok Rendah
            </h6>
            <div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="printReport()">
                    <i class="fas fa-print me-1"></i>Cetak Laporan
                </button>
                <button type="button" class="btn btn-outline-success btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fas fa-file-export me-1"></i>Export
                </button>
            </div>
        </div>
        <div class="card-body">
            @if($lowStockMedicines->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Obat</th>
                                <th>Nama Obat</th>
                                <th width="10%">Jenis</th>
                                <th width="10%">Satuan</th>
                                <th width="15%">Stok</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Status</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockMedicines as $index => $medicine)
                                @php
                                    $stockStatus = '';
                                    $statusClass = '';
                                    $progressWidth = 0;
                                    $minStock = $medicine->stok_minimum ?? 10;
                                    
                                    if ($medicine->stok == 0) {
                                        $stockStatus = 'Habis';
                                        $statusClass = 'danger';
                                        $progressWidth = 0;
                                    } elseif ($medicine->stok <= 5) {
                                        $stockStatus = 'Sangat Rendah';
                                        $statusClass = 'danger';
                                        $progressWidth = ($medicine->stok / $minStock) * 100;
                                    } elseif ($medicine->stok <= 10) {
                                        $stockStatus = 'Rendah';
                                        $statusClass = 'warning';
                                        $progressWidth = ($medicine->stok / $minStock) * 100;
                                    }
                                    
                                    $daysSinceUpdate = $medicine->updated_at
    ? now()->diffInDays($medicine->updated_at, false)
    : 0;

$daysSinceUpdate = max(0, (int) $daysSinceUpdate);

                                @endphp
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $medicine->kode_obat }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $medicine->nama_obat }}</strong>
                                        @if($medicine->batch_number)
                                            <br>
                                            <small class="text-muted">Batch: {{ $medicine->batch_number }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $medicine->jenis_obat }}</td>
                                    <td class="text-center">{{ $medicine->satuan ?? 'Tablet' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2">{{ $medicine->stok }}</div>
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $statusClass }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min($progressWidth, 100) }}%"
                                                     aria-valuenow="{{ $medicine->stok }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="{{ $minStock }}">
                                                </div>
                                            </div>
                                        </div>
                                        <small class="text-muted">Minimum: {{ $minStock }}</small>
                                    </td>
                                    <td>
                                        Rp {{ number_format($medicine->harga, 0, ',', '.') }}
                                        <br>
                                        <small class="text-primary">
                                            Total: Rp {{ number_format($medicine->stok * $medicine->harga, 0, ',', '.') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ $stockStatus }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            Diperbarui: {{ $daysSinceUpdate }} hari lalu
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('medicines.edit', $medicine) }}" 
                                               class="btn btn-warning" 
                                               title="Edit Stok">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('medicines.stock-history', $medicine) }}" 
                                               class="btn btn-info" 
                                               title="Riwayat Stok">
                                                <i class="fas fa-history"></i>
                                            </a>
                                            @if($medicine->supplier)
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick="showSupplierInfo('{{ $medicine->supplier }}')"
                                                        title="Info Supplier">
                                                    <i class="fas fa-truck"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                <td><strong>{{ $lowStockMedicines->sum('stok') }}</strong></td>
                                <td colspan="3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Summary -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card border-left-primary">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fas fa-chart-pie me-2"></i>Ringkasan Stok
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Total Nilai Stok:</small>
                                        <h5 class="mt-1">
                                            Rp {{ number_format($lowStockMedicines->sum(function($medicine) { 
                                                return $medicine->stok * $medicine->harga; 
                                            }), 0, ',', '.') }}
                                        </h5>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Estimasi Restock:</small>
                                        <h5 class="mt-1 text-success">
                                            Rp {{ number_format($lowStockMedicines->sum(function($medicine) { 
                                                $minStock = $medicine->stok_minimum ?? 10;
                                                $needed = max(0, $minStock - $medicine->stok);
                                                return $needed * $medicine->harga; 
                                            }), 0, ',', '.') }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-left-success">
                            <div class="card-body">
                                <h6 class="card-title text-success">
                                    <i class="fas fa-clipboard-list me-2"></i>Rekomendasi Restock
                                </h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($lowStockMedicines->where('stok', 0)->take(3) as $medicine)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            {{ $medicine->nama_obat }}
                                            <span class="badge bg-danger">PRIORITAS</span>
                                        </li>
                                    @endforeach
                                    @if($lowStockMedicines->where('stok', 0)->count() == 0 && $lowStockMedicines->count() > 0)
                                        <li class="list-group-item text-center text-muted">
                                            Tidak ada obat yang habis stok
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>
                    <h4 class="text-success">Tidak Ada Obat dengan Stok Rendah</h4>
                    <p class="text-muted">Semua obat memiliki stok yang cukup.</p>
                    <a href="{{ route('medicines.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-list me-2"></i>Lihat Semua Obat
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exportModalLabel">
                    <i class="fas fa-file-export me-2"></i>Export Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Format File:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exportFormat" id="exportPDF" value="pdf" checked>
                        <label class="form-check-label" for="exportPDF">
                            PDF Document
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exportFormat" id="exportExcel" value="excel">
                        <label class="form-check-label" for="exportExcel">
                            Excel Spreadsheet
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="exportFormat" id="exportCSV" value="csv">
                        <label class="form-check-label" for="exportCSV">
                            CSV File
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data yang Diexport:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="exportAll" checked>
                        <label class="form-check-label" for="exportAll">
                            Semua Data
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="exportOnlyHabis">
                        <label class="form-check-label" for="exportOnlyHabis">
                            Hanya Obat Stok Habis
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="exportData()">
                    <i class="fas fa-download me-2"></i>Export
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Supplier Info Modal -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-truck me-2"></i>Informasi Supplier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="supplierInfo"></p>
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
    .progress-bar {
        transition: width 0.6s ease;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
</style>
@endsection

@section('scripts')
<script>
    function printReport() {
        window.print();
    }
    
    function exportData() {
        const format = document.querySelector('input[name="exportFormat"]:checked').value;
        const exportAll = document.getElementById('exportAll').checked;
        const onlyHabis = document.getElementById('exportOnlyHabis').checked;
        
        // Simulate export process
        alert(`Exporting data as ${format.toUpperCase()}...\n\nIn a real application, this would download the file.`);
        $('#exportModal').modal('hide');
    }
    
    function showSupplierInfo(supplier) {
        document.getElementById('supplierInfo').textContent = supplier;
        $('#supplierModal').modal('show');
    }
    
    // Auto-refresh every 5 minutes
    setInterval(function() {
        if (!document.hidden) {
            location.reload();
        }
    }, 300000); // 5 minutes
    
    // Print styles
    window.onafterprint = function() {
        console.log('Printing completed');
    };
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
        .no-print {
            display: none !important;
        }
        .btn-group, .progress-bar {
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
    }

    /* Custom styles for low-stock page */
.stock-progress {
    height: 8px;
    margin-top: 5px;
}

.stock-progress .progress-bar {
    transition: width 0.6s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge-status {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .btn-group, .badge {
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
}
</style>
@endsection