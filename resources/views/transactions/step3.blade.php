@extends('layouts.app')

@section('title', 'Pembayaran - Transaksi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-credit-card text-primary"></i> Pembayaran Transaksi</h2>
            <div class="patient-info mt-2">
                <div class="badge bg-secondary fs-6">{{ $visit->patient->no_rekam_medis }}</div>
                <h4 class="mt-2">{{ $visit->patient->nama }}</h4>
                <p class="text-muted mb-0">
                    Dokter: {{ $visit->doctor->name }}
                </p>
            </div>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('transactions.step2', $visit) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Item
            </a>
        </div>
    </div>
    
    <!-- Step Indicator -->
    <div class="steps mb-5">
        <div class="step completed">
            <div class="step-circle">✓</div>
            <div class="step-label">Pilih Kunjungan</div>
        </div>
        <div class="step-line"></div>
        <div class="step completed">
            <div class="step-circle">✓</div>
            <div class="step-label">Tambah Item</div>
        </div>
        <div class="step-line"></div>
        <div class="step active">
            <div class="step-circle">3</div>
            <div class="step-label">Pembayaran</div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row">
        <!-- Ringkasan Transaksi -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i> Ringkasan Transaksi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Detail Item:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-end">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $key => $item)
                                    <tr>
                                        <td>
                                            {{ $item['name'] }}
                                            @if($item['note'])
                                                <br><small class="text-muted">{{ $item['note'] }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ $item['quantity'] }}</td>
                                        <td class="text-end">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                                        <td class="text-end">
                                            <h4 class="mb-0 text-success">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i> Pastikan data sudah benar!</h6>
                        <p class="mb-0">Periksa kembali item transaksi sebelum menyimpan.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Pembayaran -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i> Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('transactions.store', $visit) }}" id="paymentForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Metode Pembayaran -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Metode Pembayaran *</label>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" value="tunai" id="tunai" checked>
                                    <label class="btn btn-outline-primary w-100" for="tunai">
                                        <i class="fas fa-money-bill-wave me-2"></i> Tunai
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" value="transfer" id="transfer">
                                    <label class="btn btn-outline-primary w-100" for="transfer">
                                        <i class="fas fa-university me-2"></i> Transfer
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" value="qris" id="qris">
                                    <label class="btn btn-outline-primary w-100" for="qris">
                                        <i class="fas fa-qrcode me-2"></i> QRIS
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <input type="radio" class="btn-check" name="metode_pembayaran" value="e-wallet" id="ewallet">
                                    <label class="btn btn-outline-primary w-100" for="ewallet">
                                        <i class="fas fa-wallet me-2"></i> E-Wallet
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Transaksi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Status Transaksi *</label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="menunggu" selected>Menunggu Pembayaran</option>
                                <option value="lunas">Lunas</option>
                                <option value="batal">Batal</option>
                            </select>
                        </div>
                        
                        <!-- Bukti Pembayaran (hidden by default) -->
                        <div class="mb-4" id="proofSection" style="display: none;">
                            <label class="form-label fw-bold">Bukti Pembayaran</label>
                            <input type="file" class="form-control" name="bukti_pembayaran" id="bukti_pembayaran" accept="image/*,.pdf">
                            <div class="form-text">Unggah bukti transfer (maks. 2MB, format: jpg, png, pdf)</div>
                            <div id="proofPreview" class="mt-2"></div>
                        </div>
                        
                        <!-- Jumlah Dibayar -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Jumlah Dibayar (Rp) *</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white">Rp</span>
                                <input type="number" class="form-control" name="amount_paid" id="amount_paid" 
                                       value="{{ $totalAmount }}" min="0" required
                                       onchange="calculateChange()" onkeyup="calculateChange()">
                            </div>
                            <div class="form-text">Masukkan jumlah uang yang diterima dari pasien</div>
                        </div>
                        
                        <!-- Kembalian -->
                        <div class="mb-4">
                            <div class="alert alert-secondary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Kembalian:</span>
                                    <span id="changeAmount" class="fs-4 text-success">Rp 0</span>
                                </div>
                                <div id="changeMessage" class="mt-2 small"></div>
                            </div>
                        </div>
                        
                        <!-- Tombol Aksi -->
                        <div class="d-grid gap-2 mt-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="print_invoice" id="print_invoice" checked>
                                <label class="form-check-label" for="print_invoice">
                                    <i class="fas fa-print me-2"></i> Cetak invoice setelah simpan
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle me-2"></i> Simpan Transaksi
                            </button>
                            
                            <a href="{{ route('transactions.step2', $visit) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Kembali ke Item
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const totalAmount = {{ $totalAmount }};
    
    function calculateChange() {
        const amountPaid = parseFloat($('#amount_paid').val()) || 0;
        const change = amountPaid - totalAmount;
        
        const changeElement = $('#changeAmount');
        const messageElement = $('#changeMessage');
        
        changeElement.text('Rp ' + Math.max(0, change).toLocaleString('id-ID'));
        
        if (amountPaid === 0) {
            changeElement.removeClass('text-success text-danger').addClass('text-muted');
            messageElement.html('<span class="text-info"><i class="fas fa-info-circle"></i> Masukkan jumlah pembayaran</span>');
        } else if (change < 0) {
            changeElement.removeClass('text-success text-muted').addClass('text-danger');
            messageElement.html('<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Kurang bayar: Rp ' + Math.abs(change).toLocaleString('id-ID') + '</span>');
        } else if (change === 0) {
            changeElement.removeClass('text-danger text-muted').addClass('text-success');
            messageElement.html('<span class="text-success"><i class="fas fa-check-circle"></i> Pembayaran pas</span>');
        } else {
            changeElement.removeClass('text-danger text-muted').addClass('text-success');
            messageElement.html('<span class="text-success"><i class="fas fa-check-circle"></i> Ada kembalian</span>');
        }
    }
    
    function toggleProofSection() {
        const method = $('input[name="metode_pembayaran"]:checked').val();
        const proofSection = $('#proofSection');
        
        if (method === 'tunai') {
            proofSection.hide();
            $('#bukti_pembayaran').prop('required', false);
        } else {
            proofSection.show();
            $('#bukti_pembayaran').prop('required', true);
        }
    }
    
    // Preview bukti pembayaran
    $('#bukti_pembayaran').change(function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const preview = $('#proofPreview');
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.html(`
                        <div class="border p-2 rounded">
                            <img src="${e.target.result}" class="img-fluid" style="max-height: 150px;">
                            <small class="d-block mt-1">${file.name}</small>
                        </div>
                    `);
                }
                reader.readAsDataURL(file);
            } else {
                preview.html(`
                    <div class="alert alert-info">
                        <i class="fas fa-file-pdf"></i> ${file.name}
                    </div>
                `);
            }
        }
    });
    
    // Form validation
    $('#paymentForm').submit(function(e) {
        const amountPaid = parseFloat($('#amount_paid').val()) || 0;
        const status = $('#status').val();
        
        if (status === 'lunas' && amountPaid < totalAmount) {
            e.preventDefault();
            alert('Status "Lunas" tidak bisa dipilih jika jumlah bayar kurang dari total!');
            return false;
        }
        
        // Show loading
        $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...').prop('disabled', true);
    });
    
    // Initialize
    $(document).ready(function() {
        calculateChange();
        toggleProofSection();
        
        // Listen to payment method changes
        $('input[name="metode_pembayaran"]').change(function() {
            toggleProofSection();
        });
    });
</script>
@endsection

@section('styles')
<style>
    .steps {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 2rem;
    }
    
    .step {
        text-align: center;
        position: relative;
        min-width: 150px;
    }
    
    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #198754;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        margin: 0 auto 10px;
        transition: all 0.3s;
    }
    
    .step.completed .step-circle {
        background-color: #198754;
    }
    
    .step.active .step-circle {
        background-color: #0d6efd;
        transform: scale(1.1);
        box-shadow: 0 0 0 5px rgba(13, 110, 253, 0.2);
    }
    
    .step-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #6c757d;
    }
    
    .step.active .step-label {
        color: #0d6efd;
        font-weight: bold;
    }
    
    .step-line {
        width: 150px;
        height: 3px;
        background-color: #198754;
        margin: 0 10px;
        position: relative;
        top: -25px;
    }
    
    .patient-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border-left: 4px solid #0d6efd;
    }
    
    .btn-check:checked + .btn-outline-primary {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
    }
    
    #changeAmount {
        font-weight: bold;
    }
</style>
@endsection