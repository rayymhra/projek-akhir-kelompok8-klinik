@extends('reports.print-layout')

@section('content')
    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Pasien</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['male'] }}</div>
            <div class="stat-label">Laki-laki</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['female'] }}</div>
            <div class="stat-label">Perempuan</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['new_patients'] }}</div>
            <div class="stat-label">Pasien Baru</div>
        </div>
    </div>
    
    <!-- Summary -->
    <div class="summary-section">
        <div class="summary-title">Demografi Pasien</div>
        <p>Distribusi usia pasien:</p>
        <ul style="margin: 5px 0; padding-left: 20px;">
            @foreach($age_groups as $age => $count)
            <li>{{ $age }} tahun: {{ $count }} pasien</li>
            @endforeach
        </ul>
        <p style="margin-top: 8px;">
            Rata-rata kunjungan per pasien: {{ $stats['total'] > 0 ? number_format($data->sum('visits_count') / $stats['total'], 1) : 0 }} kunjungan
        </p>
    </div>
    
    <!-- Age Distribution Chart -->
    <div class="summary-section">
        <div class="summary-title">Distribusi Usia</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Kelompok Usia</th>
                    <th width="100">Jumlah Pasien</th>
                    <th width="100">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @foreach($age_groups as $age => $count)
                <tr>
                    <td>{{ $age }} tahun</td>
                    <td class="text-center">{{ $count }}</td>
                    <td class="text-center">{{ $stats['total'] > 0 ? number_format(($count / $stats['total']) * 100, 1) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Patient List -->
    <div class="page-break mt-3">
        <div class="summary-title">Daftar Pasien</div>
        <table class="table">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="100">No. RM</th>
                    <th>Nama Pasien</th>
                    <th width="80">Jenis Kelamin</th>
                    <th width="60">Umur</th>
                    <th width="100">Tanggal Daftar</th>
                    <th width="80">Kunjungan</th>
                    <th width="120">Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $patient)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $patient->no_rekam_medis }}</td>
                    <td>{{ $patient->nama }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $patient->jenis_kelamin == 'L' ? 'info' : 'warning' }}">
                            {{ $patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </td>
                    <td class="text-center">{{ $patient->umur }} tahun</td>
                    <td>{{ $patient->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">{{ $patient->visits_count ?? 0 }}</td>
                    <td>{{ Str::limit($patient->alamat, 30) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="no-data">Tidak ada data pasien</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Patient Statistics -->
    <div class="page-break mt-3">
        <div class="summary-section">
            <div class="summary-title">Statistik Detail</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th width="100">Jumlah</th>
                        <th width="100">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pasien Laki-laki</td>
                        <td class="text-center">{{ $stats['male'] }}</td>
                        <td class="text-center">{{ $stats['total'] > 0 ? number_format(($stats['male'] / $stats['total']) * 100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td>Pasien Perempuan</td>
                        <td class="text-center">{{ $stats['female'] }}</td>
                        <td class="text-center">{{ $stats['total'] > 0 ? number_format(($stats['female'] / $stats['total']) * 100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td>Pasien Baru (Periode ini)</td>
                        <td class="text-center">{{ $stats['new_patients'] }}</td>
                        <td class="text-center">{{ $stats['total'] > 0 ? number_format(($stats['new_patients'] / $stats['total']) * 100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td>Pasien Lama</td>
                        <td class="text-center">{{ $stats['total'] - $stats['new_patients'] }}</td>
                        <td class="text-center">{{ $stats['total'] > 0 ? number_format((($stats['total'] - $stats['new_patients']) / $stats['total']) * 100, 1) : 0 }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Top Patients by Visits -->
        @if($data->count() > 0)
        <div class="summary-section mt-3">
            <div class="summary-title">10 Pasien dengan Kunjungan Terbanyak</div>
            <table class="table">
                <thead>
                    <tr>
                        <th width="30">No</th>
                        <th>Nama Pasien</th>
                        <th width="100">No. RM</th>
                        <th width="80">Jumlah Kunjungan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data->sortByDesc('visits_count')->take(10) as $patient)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $patient->nama }}</td>
                        <td>{{ $patient->no_rekam_medis }}</td>
                        <td class="text-center">{{ $patient->visits_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection