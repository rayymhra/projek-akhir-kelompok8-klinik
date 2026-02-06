@extends('layouts.app')

@section('title', 'Tambah Item - Transaksi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="fas fa-cart-plus text-primary"></i> Tambah Item Transaksi</h2>
            <div class="patient-info mt-2">
                <div class="badge bg-secondary fs-6">{{ $visit->patient->no_rekam_medis }}</div>
                <h4 class="mt-2">{{ $visit->patient->nama }}</h4>
                <p class="text-muted mb-0">
                    Dokter: {{ $visit->doctor->name }} | 
                    Tanggal: {{ $visit->tanggal_kunjungan->format('d/m/Y') }}
                </p>
            </div>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('transactions.step1') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Ganti Pasien
            </a>
            <a href="{{ route('transactions.clearCart', $visit) }}" class="btn btn-outline-danger" 
               onclick="return confirm('Hapus semua item dari keranjang?')">
                <i class="fas fa-trash"></i> Kosongkan
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
        <div class="step active">
            <div class="step-circle">2</div>
            <div class="step-label">Tambah Item</div>
        </div>
        <div class="step-line"></div>
        <div class="step">
            <div class="step-circle">3</div>
            <div class="step-label">Pembayaran</div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row">
        <!-- Resep dari Dokter -->
        @if($visit->medicalRecord && $visit->medicalRecord->prescriptions->count() > 0)
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-prescription me-2"></i> Resep dari Dokter
                        <span class="badge bg-light text-info ms-2">{{ $visit->medicalRecord->prescriptions->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th width="30"><input type="checkbox" id="selectAllRx"></th>
                                    <th>Obat</th>
                                    <th>Jumlah</th>
                                    <th>Aturan Pakai</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visit->medicalRecord->prescriptions as $rx)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="rx-checkbox" 
                                               value="{{ $rx->id }}"
                                               data-name="{{ $rx->medicine->nama_obat }}"
                                               data-price="{{ $rx->medicine->harga }}"
                                               data-quantity="{{ $rx->jumlah }}"
                                               data-note="{{ $rx->aturan_pakai }}"
                                               data-id="{{ $rx->medicine->id }}">
                                    </td>
                                    <td>{{ $rx->medicine->nama_obat }}</td>
                                    <td>{{ $rx->jumlah }} {{ $rx->medicine->satuan }}</td>
                                    <td>{{ $rx->aturan_pakai }}</td>
                                    <td>Rp {{ number_format($rx->medicine->harga, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-info w-100 mt-3" onclick="addSelectedPrescriptions()">
                        <i class="fas fa-plus-circle me-2"></i> Tambahkan Resep Terpilih
                    </button>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Tambah Item Manual -->
        <div class="col-md-{{ $visit->medicalRecord && $visit->medicalRecord->prescriptions->count() > 0 ? '6' : '12' }} mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Item Manual
                    </h5>
                </div>
                <div class="card-body">
                    <form id="addItemForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Item</label>
                                <select class="form-select" id="itemType" onchange="toggleItemFields()">
                                    <option value="medicine">Obat</option>
                                    <option value="service">Layanan</option>
                                    <option value="other">Lainnya</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="medicineSelect">
                                <label class="form-label">Pilih Obat</label>
                                <select class="form-select" id="medicineId">
                                    <option value="">Pilih obat...</option>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->id }}"
                                                data-name="{{ $medicine->nama_obat }}"
                                                data-price="{{ $medicine->harga }}"
                                                data-stock="{{ $medicine->stok }}">
                                            {{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="serviceSelect" style="display: none;">
                                <label class="form-label">Pilih Layanan</label>
                                <select class="form-select" id="serviceId">
                                    <option value="">Pilih layanan...</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}"
                                                data-name="{{ $service->nama_layanan }}"
                                                data-price="{{ $service->tarif }}">
                                            {{ $service->nama_layanan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="otherNameField" style="display: none;">
                                <label class="form-label">Nama Item</label>
                                <input type="text" class="form-control" id="itemName" placeholder="Masukkan nama item...">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="quantity" value="1" min="1">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Harga Satuan (Rp)</label>
                                <input type="number" class="form-control" id="price" value="0" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Subtotal (Rp)</label>
                                <input type="text" class="form-control" id="subtotalPreview" value="0" readonly>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Catatan (Opsional)</label>
                                <input type="text" class="form-control" id="note" placeholder="Contoh: Aturan pakai, keterangan...">
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success w-100" onclick="addItem()">
                                    <i class="fas fa-cart-plus me-2"></i> Tambahkan ke Transaksi
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Daftar Item di Keranjang -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-warning">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart me-2"></i> Item dalam Transaksi
                <span id="cartCount" class="badge bg-light text-warning ms-2">{{ count($cartItems) }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if(count($cartItems) > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th width="100">Jumlah</th>
                                <th width="150">Harga Satuan</th>
                                <th width="150">Subtotal</th>
                                <th width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartItemsBody">
                            @foreach($cartItems as $key => $item)
                            <tr id="cartItem_{{ $key }}">
                                <td>
                                    <strong>{{ $item['name'] }}</strong>
                                    @if($item['note'])
                                        <br><small class="text-muted">{{ $item['note'] }}</small>
                                    @endif
                                </td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="removeItem('{{ $key }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-end"><strong>TOTAL</strong></td>
                                <td colspan="2">
                                    <h4 class="mb-0 text-success">Rp <span id="totalAmount">{{ number_format(collect($cartItems)->sum('subtotal'), 0, ',', '.') }}</span></h4>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('transactions.step1') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                    <a href="{{ route('transactions.step3', $visit) }}" class="btn btn-success">
                        Lanjut ke Pembayaran <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">Keranjang Kosong</h4>
                    <p class="text-muted">Tambahkan item dari resep dokter atau tambahkan item manual.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let cartItems = @json($cartItems);
    let totalAmount = {{ collect($cartItems)->sum('subtotal') }};
    
    function toggleItemFields() {
        const type = $('#itemType').val();
        
        $('#medicineSelect').toggle(type === 'medicine');
        $('#serviceSelect').toggle(type === 'service');
        $('#otherNameField').toggle(type === 'other');
        
        // Reset fields
        $('#medicineId').val('');
        $('#serviceId').val('');
        $('#itemName').val('');
        $('#price').val('0');
        calculateSubtotal();
    }
    
    function updatePriceFromSelection() {
        const type = $('#itemType').val();
        let price = 0;
        
        if (type === 'medicine') {
            const selected = $('#medicineId option:selected');
            if (selected.val()) {
                price = selected.data('price');
                $('#price').val(price);
                // Auto-fill name for display
                $('#itemName').val(selected.data('name'));
            }
        } else if (type === 'service') {
            const selected = $('#serviceId option:selected');
            if (selected.val()) {
                price = selected.data('price');
                $('#price').val(price);
                $('#itemName').val(selected.data('name'));
            }
        }
        
        calculateSubtotal();
    }
    
    function calculateSubtotal() {
        const quantity = parseInt($('#quantity').val()) || 0;
        const price = parseFloat($('#price').val()) || 0;
        const subtotal = quantity * price;
        
        $('#subtotalPreview').val(subtotal.toLocaleString('id-ID'));
    }
    
    $('#medicineId, #serviceId').change(updatePriceFromSelection);
    $('#quantity, #price').on('input', calculateSubtotal);
    
    function addItem() {
        const type = $('#itemType').val();
        let itemId = '';
        let name = '';
        let price = parseFloat($('#price').val()) || 0;
        const quantity = parseInt($('#quantity').val()) || 0;
        const note = $('#note').val();
        
        if (type === 'medicine') {
            itemId = $('#medicineId').val();
            if (!itemId) {
                alert('Pilih obat terlebih dahulu');
                return;
            }
            name = $('#medicineId option:selected').data('name');
        } else if (type === 'service') {
            itemId = $('#serviceId').val();
            if (!itemId) {
                alert('Pilih layanan terlebih dahulu');
                return;
            }
            name = $('#serviceId option:selected').data('name');
        } else {
            name = $('#itemName').val().trim();
            if (!name) {
                alert('Masukkan nama item');
                return;
            }
        }
        
        if (price <= 0) {
            alert('Harga harus lebih dari 0');
            return;
        }
        
        if (quantity <= 0) {
            alert('Jumlah harus lebih dari 0');
            return;
        }
        
        // Check stock for medicine
        if (type === 'medicine') {
            const stock = $('#medicineId option:selected').data('stock');
            if (quantity > stock) {
                alert(`Stok tidak mencukupi! Stok tersedia: ${stock}`);
                return;
            }
        }
        
        const formData = {
            _token: '{{ csrf_token() }}',
            type: type,
            item_id: itemId,
            name: name,
            quantity: quantity,
            price: price,
            note: note
        };
        
        $.post('{{ route("transactions.addToCart", $visit) }}', formData)
            .done(function(response) {
                if (response.success) {
                    // Add to local cart
                    const itemKey = Date.now().toString();
                    cartItems[itemKey] = {
                        name: name,
                        quantity: quantity,
                        price: price,
                        note: note,
                        subtotal: quantity * price
                    };
                    
                    // Update table
                    updateCartDisplay();
                    
                    // Reset form
                    $('#addItemForm')[0].reset();
                    $('#price').val('0');
                    calculateSubtotal();
                    
                    alert('Item berhasil ditambahkan');
                }
            })
            .fail(function() {
                alert('Gagal menambahkan item');
            });
    }
    
    function addSelectedPrescriptions() {
        const selected = $('.rx-checkbox:checked');
        if (selected.length === 0) {
            alert('Pilih minimal satu resep');
            return;
        }
        
        const prescriptionIds = selected.map(function() {
            return $(this).val();
        }).get();
        
        $.post('{{ route("transactions.addPrescriptionsToCart", $visit) }}', {
            _token: '{{ csrf_token() }}',
            prescription_ids: prescriptionIds
        })
        .done(function(response) {
            if (response.success) {
                // Reload page to show updated cart
                location.reload();
            }
        })
        .fail(function() {
            alert('Gagal menambahkan resep');
        });
    }
    
    function removeItem(itemKey) {
        if (!confirm('Hapus item ini dari transaksi?')) return;
        
        $.post('{{ route("transactions.removeFromCart", $visit) }}', {
            _token: '{{ csrf_token() }}',
            item_key: itemKey
        })
        .done(function(response) {
            if (response.success) {
                // Remove from local cart
                delete cartItems[itemKey];
                updateCartDisplay();
            }
        })
        .fail(function() {
            alert('Gagal menghapus item');
        });
    }
    
    function updateCartDisplay() {
        // This function would update the cart display without page reload
        // For simplicity, we'll reload the page
        location.reload();
    }
    
    // Select all prescriptions
    $('#selectAllRx').change(function() {
        $('.rx-checkbox').prop('checked', $(this).prop('checked'));
    });
    
    // Initialize
    $(document).ready(function() {
        toggleItemFields();
        calculateSubtotal();
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
        background-color: #e9ecef;
        color: #6c757d;
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
        color: white;
    }
    
    .step.active .step-circle {
        background-color: #0d6efd;
        color: white;
        transform: scale(1.1);
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
        background-color: #e9ecef;
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
    
    .table th {
        border-top: none;
        font-weight: 600;
    }
</style>
@endsection