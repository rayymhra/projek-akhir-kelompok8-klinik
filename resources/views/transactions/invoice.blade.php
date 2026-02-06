<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $transaction->visit->patient->nama }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            border: 2px solid #333;
            border-radius: 10px;
            background-color: #fff;
        }
        
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        
        .clinic-name {
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .clinic-address {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin: 20px 0;
        }
        
        .patient-info, .transaction-info {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #3498db;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .info-item {
            margin-bottom: 8px;
        }
        
        .info-label {
            font-weight: bold;
            color: #2c3e50;
            display: inline-block;
            min-width: 120px;
        }
        
        .table-container {
            margin: 25px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 10px 8px;
            border: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .total-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .grand-total {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
            border-top: 2px solid #ddd;
            padding-top: 15px;
            margin-top: 15px;
        }
        
        .payment-info {
            margin-top: 30px;
            padding: 20px;
            background-color: #e8f4fd;
            border-radius: 5px;
            border: 1px solid #3498db;
        }
        
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin: 50px 0 10px;
        }
        
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .status-lunas {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-batal {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .invoice-container {
                border: none;
                padding: 0;
            }
            
            .no-print {
                display: none;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="clinic-name">PRIMA MEDIKA KLINIK</div>
            <div class="clinic-address">
                Jl. Kesehatan No. 123, Jakarta | Telp: (021) 12345678
            </div>
            <div class="invoice-title">INVOICE TRANSAKSI</div>
        </div>
        
        <!-- Patient & Transaction Info -->
        <div class="info-grid">
            <div class="patient-info">
                <h3>Informasi Pasien</h3>
                <div class="info-item">
                    <span class="info-label">Nama Pasien:</span>
                    {{ $transaction->visit->patient->nama }}
                </div>
                <div class="info-item">
                    <span class="info-label">No. Rekam Medis:</span>
                    {{ $transaction->visit->patient->no_rekam_medis }}
                </div>
                <div class="info-item">
                    <span class="info-label">Jenis Kelamin:</span>
                    {{ $transaction->visit->patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                </div>
                <div class="info-item">
                    <span class="info-label">Usia:</span>
                    {{ $transaction->visit->patient->usia }} tahun
                </div>
            </div>
            
            <div class="transaction-info">
                <h3>Informasi Transaksi</h3>
                <div class="info-item">
                    <span class="info-label">No. Transaksi:</span>
                    TRX-{{ str_pad($transaction->id, 6, '0', STR_PAD_LEFT) }}
                </div>
                <div class="info-item">
                    <span class="info-label">Tanggal:</span>
                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                </div>
                <div class="info-item">
                    <span class="info-label">Dokter:</span>
                    {{ $transaction->visit->doctor->name }}
                </div>
                <div class="info-item">
                    <span class="info-label">Status:</span>
                    <span class="status-badge status-{{ $transaction->status }}">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Items Table -->
        <div class="table-container">
            <h3>Rincian Transaksi</h3>
            <table>
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Item / Layanan</th>
                        <th width="100" class="text-right">Jumlah</th>
                        <th width="150" class="text-right">Harga Satuan</th>
                        <th width="150" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{ $detail->item_name }}
                            @if($detail->note)
                                <br><small><em>{{ $detail->note }}</em></small>
                            @endif
                        </td>
                        <td class="text-right">{{ $detail->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($detail->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Total Section -->
        <div class="total-section">
            <div class="total-row">
                <span>Total Item:</span>
                <span>{{ $transaction->details->count() }} item</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL BIAYA:</span>
                <span>Rp {{ number_format($transaction->total_biaya, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <!-- Payment Information -->
        <div class="payment-info">
            <h3>Informasi Pembayaran</h3>
            <div class="info-item">
                <span class="info-label">Metode Pembayaran:</span>
                {{ ucfirst($transaction->metode_pembayaran) }}
            </div>
            <div class="info-item">
                <span class="info-label">Jumlah Dibayar:</span>
                Rp {{ number_format($transaction->jumlah_dibayar ?? $transaction->total_biaya, 0, ',', '.') }}
            </div>
            @if($transaction->kembalian > 0)
            <div class="info-item">
                <span class="info-label">Kembalian:</span>
                Rp {{ number_format($transaction->kembalian, 0, ',', '.') }}
            </div>
            @endif
        </div>
        
        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Pasien / Keluarga</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div>Kasir</div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Invoice ini sah dan dapat digunakan sebagai bukti pembayaran</p>
            <p>Terima kasih telah mempercayakan kesehatan Anda kepada kami</p>
            <p>** Simpan invoice ini untuk keperluan administrasi **</p>
            <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
        
        <!-- Print Button (hidden when printing) -->
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="padding: 10px 30px; font-size: 16px; background-color: #2c3e50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                🖨️ Cetak Invoice
            </button>
            <button onclick="history.back()" style="padding: 10px 30px; font-size: 16px; background-color: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                ✕ Tutup
            </button>
        </div>
    </div>
    
    <script>
        // Auto print if needed
        @if(request('print') || session('print_invoice'))
            window.onload = function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 1000);
            };
        @endif
    </script>
</body>
</html>