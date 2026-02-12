@extends('reports.print-layout')

@section('content')
    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Jenis Obat</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['low_stock'] }}</div>
            <div class="stat-label">Stok Rendah</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['out_of_stock'] }}</div>
            <div class="stat-label">Habis</div>
        </div>
        <div class="stat-card">
            <div class="stat-value currency">Rp {{ number_format($stats['total_stock_value'], 0, ',', '.') }}</div>
            <div class="stat-label">Nilai Stok</div>
        </div>
    </div>
    
    <!-- Summary -->
    <div class="summary-section">
        <div class="summary-title">Ringkasan Inventori Obat</div>
        <p>Total nilai stok obat: <strong class="currency">Rp {{ number_format($stats['total_stock_value'], 0, ',', '.') }}</strong></p>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li>Jenis obat tersedia: {{ $stats['total'] }} jenis</li>
            <li>Stok rendah (≤ 10): {{ $stats['low_stock'] }} jenis</li>
            <li>Stok habis: {{ $stats['out_of_stock'] }} jenis</li>
            <li>Obat yang akan kadaluwarsa: {{ $data->filter(function($m) { return $m->expired_date && $m->expired_date->diffInDays(now()) <= 30; })->count() }} jenis</li>
        </ul>
    </div>
    
    <!-- Medicine List -->
    <div class="mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="80">Kode Obat</th>
                    <th>Nama Obat</th>
                    <th width="80">Jenis</th>
                    <th width="60">Stok</th>
                    <th width="80">Satuan</th>
                    <th width="100">Harga</th>
                    <th width="100">Nilai Stok</th>
                    <th width="100">Kadaluwarsa</th>
                    <th width="80">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $medicine)
                @php
                    $stockValue = $medicine->stok * $medicine->harga;
                    $isExpiring = $medicine->expired_date && $medicine->expired_date->diffInDays(now()) <= 30;
                    $isExpired = $medicine->expired_date && $medicine->expired_date->isPast();
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $medicine->kode_obat }}</td>
                    <td>{{ $medicine->nama_obat }}</td>
                    <td>{{ $medicine->jenis_obat }}</td>
                    <td class="text-center {{ $medicine->stok <= 10 ? 'currency' : '' }}">
                        {{ $medicine->stok }}
                        @if($medicine->stok <= 10)
                            <br><small style="color: #d97706;">(Rendah)</small>
                        @endif
                    </td>
                    <td>{{ $medicine->satuan ?? 'pcs' }}</td>
                    <td class="text-right currency">Rp {{ number_format($medicine->harga, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($stockValue, 0, ',', '.') }}</td>
                    <td>
                        @if($medicine->expired_date)
                            {{ $medicine->expired_date->format('d/m/Y') }}
                            @if($isExpiring)
                                <br><small class="badge badge-warning">Segera</small>
                            @endif
                            @if($isExpired)
                                <br><small class="badge badge-danger">Kadaluwarsa</small>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($medicine->stok == 0)
                            <span class="badge badge-danger">Habis</span>
                        @elseif($medicine->stok <= 10)
                            <span class="badge badge-warning">Rendah</span>
                        @else
                            <span class="badge badge-success">Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="no-data">Tidak ada data obat</td>
                </tr>
                @endforelse
            </tbody>
            @if($data->count() > 0)
            <tfoot>
                <tr style="background: #f1f5f9; font-weight: 600;">
                    <td colspan="7" class="text-right">Total Nilai Stok:</td>
                    <td class="text-right currency">Rp {{ number_format($stats['total_stock_value'], 0, ',', '.') }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    
    <!-- Most Prescribed Medicines -->
    <div class="page-break mt-3">
        @if(isset($most_prescribed) && $most_prescribed->count() > 0)
        <div class="summary-section">
            <div class="summary-title">10 Obat Paling Sering Diresepkan</div>
            <table class="table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th>Nama Obat</th>
                        <th width="100">Kode</th>
                        <th width="80">Jumlah Resep</th>
                        <th width="80">Stok Tersedia</th>
                        <th width="100">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalPrescriptions = $most_prescribed->sum(function($m) { 
                            return $m->prescriptions->sum('jumlah'); 
                        });
                    @endphp
                    @foreach($most_prescribed as $medicine)
                    @php
                        $prescriptionCount = $medicine->prescriptions->sum('jumlah');
                        $percentage = $totalPrescriptions > 0 ? ($prescriptionCount / $totalPrescriptions) * 100 : 0;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $medicine->nama_obat }}</td>
                        <td>{{ $medicine->kode_obat }}</td>
                        <td class="text-center">{{ $prescriptionCount }}</td>
                        <td class="text-center {{ $medicine->stok <= 10 ? 'text-danger' : '' }}">
                            {{ $medicine->stok }}
                            @if($medicine->stok <= 10)
                                <br><small>Stok Rendah</small>
                            @endif
                        </td>
                        <td class="text-center">{{ number_format($percentage, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Stock Alert -->
        <div class="summary-section mt-3">
            <div class="summary-title">Peringatan Stok</div>
            
            <!-- Low Stock -->
            @php
                $lowStockMedicines = $data->filter(function($m) { return $m->stok > 0 && $m->stok <= 10; });
                $outOfStockMedicines = $data->filter(function($m) { return $m->stok == 0; });
                $expiringMedicines = $data->filter(function($m) { 
                    return $m->expired_date && $m->expired_date->diffInDays(now()) <= 30 && !$m->expired_date->isPast(); 
                });
            @endphp
            
            @if($lowStockMedicines->count() > 0)
            <h6 style="font-size: 11px; margin: 15px 0 8px 0; color: #d97706;">Stok Rendah (≤ 10)</h6>
            <table class="table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th>Nama Obat</th>
                        <th width="80">Kode</th>
                        <th width="60">Stok</th>
                        <th width="100">Reorder Level</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockMedicines as $medicine)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $medicine->nama_obat }}</td>
                        <td>{{ $medicine->kode_obat }}</td>
                        <td class="text-center text-danger">{{ $medicine->stok }}</td>
                        <td class="text-center">Segera tambah stok</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            
            <!-- Out of Stock -->
            @if($outOfStockMedicines->count() > 0)
            <h6 style="font-size: 11px; margin: 15px 0 8px 0; color: #dc2626;">Stok Habis</h6>
            <table class="table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th>Nama Obat</th>
                        <th width="80">Kode</th>
                        <th width="100">Harga</th>
                        <th width="100">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outOfStockMedicines as $medicine)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $medicine->nama_obat }}</td>
                        <td>{{ $medicine->kode_obat }}</td>
                        <td class="text-right currency">Rp {{ number_format($medicine->harga, 0, ',', '.') }}</td>
                        <td class="text-center"><span class="badge badge-danger">Perlu restock</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            
            <!-- Expiring Soon -->
            @if($expiringMedicines->count() > 0)
            <h6 style="font-size: 11px; margin: 15px 0 8px 0; color: #d97706;">Akan Kadaluwarsa (30 hari)</h6>
            <table class="table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th>Nama Obat</th>
                        <th width="80">Kode</th>
                        <th width="100">Kadaluwarsa</th>
                        <th width="60">Stok</th>
                        <th width="100">Sisa Hari</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiringMedicines as $medicine)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $medicine->nama_obat }}</td>
                        <td>{{ $medicine->kode_obat }}</td>
                        <td>{{ $medicine->expired_date->format('d/m/Y') }}</td>
                        <td class="text-center">{{ $medicine->stok }}</td>
                        <td class="text-center">{{ $medicine->expired_date->diffInDays(now()) }} hari</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
            
            @if($lowStockMedicines->count() == 0 && $outOfStockMedicines->count() == 0 && $expiringMedicines->count() == 0)
            <p style="text-align: center; color: #059669; padding: 20px;">
                <i class="fas fa-check-circle"></i> Semua stok obat dalam kondisi baik
            </p>
            @endif
        </div>
    </div>
    
    <!-- Medicine Categories -->
    <div class="page-break mt-3">
        <div class="summary-section">
            <div class="summary-title">Distribusi Obat per Kategori</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Jenis Obat</th>
                        <th width="100">Jumlah Item</th>
                        <th width="100">Total Stok</th>
                        <th width="120">Nilai Stok</th>
                        <th width="100">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $categories = $data->groupBy('jenis_obat');
                    @endphp
                    @foreach($categories as $category => $medicines)
                    <tr>
                        <td>{{ $category }}</td>
                        <td class="text-center">{{ $medicines->count() }}</td>
                        <td class="text-center">{{ $medicines->sum('stok') }}</td>
                        <td class="text-right currency">Rp {{ number_format($medicines->sum(function($m) { return $m->stok * $m->harga; }), 0, ',', '.') }}</td>
                        <td class="text-center">{{ number_format(($medicines->count() / $data->count()) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection