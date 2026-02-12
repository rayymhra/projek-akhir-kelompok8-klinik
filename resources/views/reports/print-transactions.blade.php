@extends('reports.print-layout')

@section('content')
    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Transaksi</div>
        </div>
        <div class="stat-card">
            <div class="stat-value currency">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['lunas'] }}</div>
            <div class="stat-label">Lunas</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $stats['menunggu'] }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
    </div>
    
    <!-- Summary -->
    <div class="summary-section">
        <div class="summary-title">Ringkasan Transaksi</div>
        <p>Total pendapatan dalam periode ini: <strong class="currency">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</strong></p>
        <ul style="margin: 5px 0; padding-left: 20px;">
            <li>Jumlah transaksi: {{ $stats['total'] }} transaksi</li>
            <li>Transaksi lunas: {{ $stats['lunas'] }} transaksi</li>
            <li>Transaksi menunggu: {{ $stats['menunggu'] }} transaksi</li>
            <li>Transaksi batal: {{ $stats['batal'] ?? 0 }} transaksi</li>
        </ul>
    </div>
    
    <!-- Payment Method Breakdown -->
    <div class="summary-section">
        <div class="summary-title">Metode Pembayaran</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Metode Pembayaran</th>
                    <th width="100">Jumlah Transaksi</th>
                    <th width="150">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $methods = $data->groupBy('metode_pembayaran');
                @endphp
                @foreach($methods as $method => $transactions)
                <tr>
                    <td>{{ ucfirst($method) }}</td>
                    <td class="text-center">{{ $transactions->count() }}</td>
                    <td class="text-right currency">Rp {{ number_format($transactions->sum('total_biaya'), 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Detailed Transactions -->
    <div class="page-break mt-3">
        <div class="summary-title">Detail Transaksi</div>
        <table class="table">
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th width="80">Tanggal</th>
                    <th width="100">No. Transaksi</th>
                    <th>Pasien</th>
                    <th width="120">Metode Bayar</th>
                    <th width="100">Status</th>
                    <th width="120">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $transaction)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td>{{ $transaction->kode_transaksi ?? 'TRX-' . $transaction->id }}</td>
                    <td>{{ $transaction->visit->patient->nama }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $transaction->metode_pembayaran == 'tunai' ? 'primary' : ($transaction->metode_pembayaran == 'transfer' ? 'success' : 'info') }}">
                            {{ ucfirst($transaction->metode_pembayaran) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-{{ $transaction->status == 'lunas' ? 'success' : ($transaction->status == 'menunggu' ? 'warning' : 'danger') }}">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </td>
                    <td class="text-right currency">Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="no-data">Tidak ada data transaksi</td>
                </tr>
                @endforelse
            </tbody>
            @if($data->count() > 0)
            <tfoot>
                <tr style="background: #f1f5f9; font-weight: 600;">
                    <td colspan="6" class="text-right">Total Pendapatan:</td>
                    <td class="text-right currency">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
    
    <!-- Transaction Items -->
    <div class="page-break mt-3">
        <div class="summary-title">Detail Item Transaksi</div>
        @foreach($data->where('status', 'lunas') as $transaction)
        <div style="margin-bottom: 15px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <strong>{{ $transaction->kode_transaksi ?? 'TRX-' . $transaction->id }}</strong>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div style="font-size: 10px; color: #64748b; margin-bottom: 8px;">
                Pasien: {{ $transaction->visit->patient->nama }} | Dokter: {{ $transaction->visit->doctor->name ?? '-' }}
            </div>
            <table style="width: 100%; font-size: 9px;">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th width="60">Jumlah</th>
                        <th width="80">Harga</th>
                        <th width="90">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $detail)
                    <tr>
                        <td>{{ $detail->item_name }}</td>
                        <td class="text-center">{{ $detail->quantity }}</td>
                        <td class="text-right currency">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td class="text-right currency">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr style="border-top: 1px solid #e2e8f0;">
                        <td colspan="3" class="text-right"><strong>Total:</strong></td>
                        <td class="text-right currency"><strong>Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
@endsection