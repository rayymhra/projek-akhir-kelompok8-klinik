@extends('layouts.app')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cash-register me-2"></i>Manajemen Transaksi
                    </h5>
                    <a href="{{ route('transactions.step1') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>Transaksi Baru
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="">Semua Status</option>
                                    <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="start_date" 
                                       value="{{ request('start_date') }}" placeholder="Dari Tanggal">
                            </div>
                            <div class="col-md-3">
                                <input type="date" class="form-control" name="end_date" 
                                       value="{{ request('end_date') }}" placeholder="Sampai Tanggal">
                            </div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Cari pasien..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">Total Transaksi</h6>
                                        <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                    </div>
                                    <i class="fas fa-list fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">Menunggu</h6>
                                        <h4 class="mb-0">{{ $stats['pending'] }}</h4>
                                    </div>
                                    <i class="fas fa-clock fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">Lunas</h6>
                                        <h4 class="mb-0">{{ $stats['completed'] }}</h4>
                                    </div>
                                    <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-0">Batal</h6>
                                        <h4 class="mb-0">{{ $stats['cancelled'] }}</h4>
                                    </div>
                                    <i class="fas fa-times-circle fa-2x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Summary -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary mb-1">
                                            <i class="fas fa-calendar-day me-2"></i>Hari Ini
                                        </h6>
                                        <div class="d-flex align-items-center">
                                            <div class="me-4">
                                                <h4 class="mb-0">{{ $stats['today'] }}</h4>
                                                <small class="text-muted">Transaksi</small>
                                            </div>
                                            <div>
                                                <h4 class="mb-0">Rp {{ number_format($stats['today_amount'], 0, ',', '.') }}</h4>
                                                <small class="text-muted">Pendapatan</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <button class="btn btn-outline-primary" onclick="window.print()">
                                            <i class="fas fa-print me-2"></i>Cetak Laporan Harian
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Pasien</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>T-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                    <br>
                                    <small class="text-muted">Kunj: V-{{ str_pad($transaction->visit_id, 6, '0', STR_PAD_LEFT) }}</small>
                                </td>
                                <td>
                                    <strong>{{ $transaction->visit->patient->nama }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $transaction->visit->patient->no_rekam_medis }}</small>
                                </td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <strong class="text-primary">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @php
                                        $methodColors = [
                                            'tunai' => 'success',
                                            'transfer' => 'primary',
                                            'qris' => 'info',
                                            'e-wallet' => 'warning'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $methodColors[$transaction->metode_pembayaran] ?? 'secondary' }}">
                                        {{ ucfirst($transaction->metode_pembayaran) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'menunggu' => 'warning',
                                            'lunas' => 'success',
                                            'batal' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$transaction->status] }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('transactions.show', $transaction) }}" 
                                           class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- @if($transaction->status != 'lunas')
                                        <a href="{{ route('transactions.edit', $transaction) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif --}}
                                        @if($transaction->status === 'menunggu')
<form action="{{ route('transactions.confirm', $transaction) }}" 
      method="POST" 
      class="d-inline"
      onsubmit="return confirm('Konfirmasi pembayaran transaksi ini?')">
    @csrf
    <button class="btn btn-success" title="Konfirmasi Pembayaran">
        <i class="fas fa-check"></i> Lunas
    </button>
</form>
@endif

                                        <a href="{{ route('transactions.print', $transaction) }}" 
                                           class="btn btn-secondary" title="Cetak" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-cash-register fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum ada transaksi</h5>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
    }
</style>
@endsection