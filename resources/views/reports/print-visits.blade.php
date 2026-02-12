@extends('reports.print-layout')

@section('content')
    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Kunjungan</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['menunggu'] }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['diperiksa'] }}</div>
            <div class="stat-label">Diperiksa</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['selesai'] }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
    
    <!-- Summary -->
    <div class="summary-section">
        <div class="summary-title">Ringkasan Kunjungan</div>
        <p>Total kunjungan dalam periode ini sebanyak <strong>{{ $stats['total'] }}</strong> kunjungan dengan rincian:</p>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li>Status Menunggu: {{ $stats['menunggu'] }} kunjungan</li>
            <li>Status Diperiksa: {{ $stats['diperiksa'] }} kunjungan</li>
            <li>Status Selesai: {{ $stats['selesai'] }} kunjungan</li>
        </ul>
        @if(!empty($filter['doctor_id']))
        <p style="margin-top: 8px;">
            <small>Filter dokter: {{ \App\Models\User::find($filter['doctor_id'])->name ?? 'Dokter Tidak Ditemukan' }}</small>
        </p>
        @endif
    </div>
    
    <!-- Detailed Table -->
    <div class="mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="80">Tanggal</th>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th width="100">Dokter</th>
                    <th width="80">Status</th>
                    <th width="80">Diagnosa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $visit)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $visit->tanggal_kunjungan->format('d/m/Y') }}</td>
                    <td>{{ $visit->patient->no_rekam_medis }}</td>
                    <td>{{ $visit->patient->nama }}</td>
                    <td>{{ $visit->doctor->name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $visit->status == 'selesai' ? 'success' : ($visit->status == 'diperiksa' ? 'info' : 'warning') }}">
                            {{ ucfirst($visit->status) }}
                        </span>
                    </td>
                    <td>{{ $visit->medicalRecord->diagnosa ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data">Tidak ada data kunjungan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Daily Statistics -->
    <div class="page-break mt-3">
        <div class="summary-section">
            <div class="summary-title">Statistik Harian</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Menunggu</th>
                        <th>Diperiksa</th>
                        <th>Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grouped as $date => $stats)
                    <tr>
                        <td>{{ date('d/m/Y', strtotime($date)) }}</td>
                        <td class="text-center">{{ $stats['total'] }}</td>
                        <td class="text-center">{{ $stats['menunggu'] }}</td>
                        <td class="text-center">{{ $stats['diperiksa'] }}</td>
                        <td class="text-center">{{ $stats['selesai'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection