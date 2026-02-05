@extends('layouts.app')

@section('title', 'Riwayat Stok Obat')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Riwayat Stok Obat</h5>
                <small class="text-muted">{{ $medicine->nama_obat }} ({{ $medicine->kode_obat ?? 'No Code' }})</small>
            </div>
            <a href="{{ route('medicines.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card-body">

            {{-- Medicine Info --}}
            <div class="row mb-4">
                <div class="col-md-3"><strong>Stok Saat Ini:</strong> {{ $medicine->stok }}</div>
                <div class="col-md-3"><strong>Satuan:</strong> {{ $medicine->satuan }}</div>
                <div class="col-md-3"><strong>Kategori:</strong> {{ $medicine->kategori ?? '-' }}</div>
                <div class="col-md-3"><strong>Kedaluwarsa:</strong> {{ $medicine->expired_date?->format('d M Y') ?? '-' }}</div>
            </div>

            {{-- Stock History Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Stok Setelah</th>
                            <th>Keterangan</th>
                            <th>Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stockHistories as $history)
                        <tr>
                            <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>

                            <td>
                                @if($history->type == 'masuk')
                                    <span class="badge bg-success">Stok Masuk</span>
                                @elseif($history->type == 'keluar')
                                    <span class="badge bg-danger">Stok Keluar</span>
                                @else
                                    <span class="badge bg-secondary">Penyesuaian</span>
                                @endif
                            </td>

                            <td class="{{ $history->type == 'masuk' ? 'text-success' : 'text-danger' }}">
                                {{ $history->type == 'masuk' ? '+' : '-' }}{{ $history->quantity }}
                            </td>

                            <td><strong>{{ $history->stock_after }}</strong></td>

                            <td>{{ $history->note ?? '-' }}</td>

                            <td>{{ $history->user->name ?? 'System' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada riwayat stok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $stockHistories->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
