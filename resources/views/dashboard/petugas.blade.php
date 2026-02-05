@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user-tie me-2"></i>Dashboard Petugas Pendaftaran
            </h1>
            <div class="btn-group">
                <a href="{{ route('visits.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Kunjungan Baru
                </a>
                <a href="{{ route('patients.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus me-2"></i>Pasien Baru
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Utama Petugas -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Antrian Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['todayVisits'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="badge bg-warning">{{ $stats['waitingVisits'] }} Menunggu</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Pasien Baru Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['newPatientsToday'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success mr-2">
                                <i class="fas fa-user-plus"></i> Terdaftar
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
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
                            Total Pasien Terdaftar
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['totalPatients'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-info mr-2">
                                <i class="fas fa-database"></i> Database
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-injured fa-2x text-gray-300"></i>
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
                            Kunjungan Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['monthlyVisits'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-warning mr-2">
                                <i class="fas fa-calendar-alt"></i> {{ now()->format('F') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Antrian Sekarang -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list-ol me-2"></i>Antrian Saat Ini
                </h6>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshQueue()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <a href="{{ route('visits.index', ['status' => 'menunggu']) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye me-1"></i> Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="queueContainer">
                    @foreach($stats['currentQueue'] as $queue)
                    <div class="col-md-4 mb-3">
                        <div class="card queue-card border-left-{{ $queue['priority'] ? 'danger' : 'info' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title mb-1">
                                            <span class="badge bg-{{ $queue['priority'] ? 'danger' : 'primary' }} me-2">
                                                {{ $queue['queue_number'] }}
                                            </span>
                                            {{ $queue['patient_name'] }}
                                        </h5>
                                        <p class="card-text mb-1">
                                            <small class="text-muted">
                                                <i class="fas fa-user-md me-1"></i>{{ $queue['doctor_name'] }}
                                            </small>
                                        </p>
                                        <p class="card-text mb-0">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $queue['time'] }}
                                            </small>
                                        </p>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('patients.show', $queue['patient_id']) }}">
                                                    <i class="fas fa-user me-2"></i>Detail Pasien
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('visits.updateStatus', $queue['id']) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="diperiksa">
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-play me-2"></i>Mulai Pemeriksaan
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" 
                                                   onclick="cancelQueue({{ $queue['id'] }})">
                                                    <i class="fas fa-times me-2"></i>Batalkan
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @if($queue['note'])
                                <div class="mt-2">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-sticky-note me-1"></i>{{ $queue['note'] }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if(empty($stats['currentQueue']))
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada antrian saat ini</h5>
                    <p class="text-muted">Semua pasien telah dilayani</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Panel Cepat -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-bolt me-2"></i>Panel Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('patients.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Daftar Pasien Baru
                    </a>
                    <a href="{{ route('visits.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-calendar-plus me-2"></i>Kunjungan Baru
                    </a>
                    <a href="{{ route('patients.index') }}" class="btn btn-info btn-lg">
                        <i class="fas fa-search me-2"></i>Cari Pasien
                    </a>
                    <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#printQueueModal">
                        <i class="fas fa-print me-2"></i>Cetak Antrian
                    </button>
                </div>
                
                <!-- Pencarian Cepat -->
                <div class="mt-4">
                    <h6 class="text-primary mb-3">Pencarian Cepat Pasien</h6>
                    <form id="quickSearchForm">
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   placeholder="Masukkan nama atau no. RM" 
                                   id="quickSearchInput">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    <div id="quickSearchResults" class="mt-3" style="max-height: 200px; overflow-y: auto;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pasien Baru Terdaftar -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-user-plus me-2"></i>Pasien Baru Hari Ini
                </h6>
                <a href="{{ route('patients.index') }}" class="btn btn-sm btn-success">
                    <i class="fas fa-list me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>No. RM</th>
                                <th>Nama</th>
                                <th>Waktu</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recentPatients'] as $patient)
                            <tr>
                                <td><span class="badge bg-secondary">{{ $patient->no_rekam_medis }}</span></td>
                                <td>
                                    <div class="font-weight-bold">{{ $patient->nama }}</div>
                                    <small class="text-muted">{{ $patient->jenis_kelamin == 'L' ? 'L' : 'P' }} • {{ $patient->umur }} thn</small>
                                </td>
                                <td>{{ $patient->created_at->format('H:i') }}</td>
                                <td>
                                    <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada pasien baru hari ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kunjungan Hari Ini -->
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Riwayat Kunjungan Hari Ini
                </h6>
                <a href="{{ route('visits.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-history me-1"></i> Riwayat Lengkap
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pasien</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['todaysVisits'] as $visit)
                            <tr>
                                <td>{{ $visit->created_at->format('H:i') }}</td>
                                <td>{{ $visit->patient->nama }}</td>
                                <td>
                                    <span class="badge badge-status-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($visit->status == 'selesai' && !$visit->transaction)
                                        <a href="{{ route('transactions.create', ['visit' => $visit->id]) }}" 
                                           class="btn btn-success" title="Buat Transaksi">
                                            <i class="fas fa-cash-register"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('visits.index') }}" class="btn btn-info" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada kunjungan hari ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Antrian -->
<div class="modal fade" id="printQueueModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cetak Nomor Antrian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="printQueueForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Pilih Dokter</label>
                        <select class="form-select" id="doctorSelect" required>
                            <option value="">Pilih Dokter...</option>
                            @foreach($stats['doctors'] as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prioritas</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="priorityCheck">
                            <label class="form-check-label" for="priorityCheck">
                                Antrian Prioritas (Lansia/Hamil/Disabilitas)
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="queueNote" rows="2"></textarea>
                    </div>
                </form>
                <div id="queuePreview" class="d-none mt-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4 class="text-primary">KLINIK PRIMA MEDIKA</h4>
                            <h1 class="display-1 text-danger" id="previewQueueNumber">A001</h1>
                            <h5 id="previewDoctorName">Dr. Andi Wijaya</h5>
                            <p class="text-muted" id="previewDateTime">{{ now()->format('d/m/Y H:i') }}</p>
                            <small id="previewPriorityNote" class="text-danger d-none">ANTRIAN PRIORITAS</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="generateQueueNumber()">
                    <i class="fas fa-barcode me-2"></i>Generate
                </button>
                <button type="button" class="btn btn-success d-none" id="printButton" onclick="printQueue()">
                    <i class="fas fa-print me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .queue-card {
        transition: transform 0.2s;
        cursor: pointer;
    }
    
    .queue-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .queue-card.border-left-primary {
        border-left: 4px solid #4e73df !important;
    }
    
    .queue-card.border-left-danger {
        border-left: 4px solid #e74a3b !important;
    }
    
    .priority-badge {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-refresh antrian setiap 30 detik
        let autoRefresh = setInterval(refreshQueue, 30000);
        
        // Quick search functionality
        const quickSearchInput = document.getElementById('quickSearchInput');
        const quickSearchResults = document.getElementById('quickSearchResults');
        
        quickSearchInput.addEventListener('input', function(e) {
            const query = e.target.value;
            if (query.length >= 2) {
                searchPatients(query);
            } else {
                quickSearchResults.innerHTML = '';
            }
        });
        
        document.getElementById('quickSearchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const query = quickSearchInput.value;
            if (query.length >= 2) {
                searchPatients(query);
            }
        });
        
        // Dokter select change untuk preview
        document.getElementById('doctorSelect').addEventListener('change', function() {
            updateQueuePreview();
        });
        
        document.getElementById('priorityCheck').addEventListener('change', function() {
            updateQueuePreview();
        });
        
        // Play notification sound for new queue
        let lastQueueCount = {{ count($stats['currentQueue']) }};
        
        function refreshQueue() {
            fetch('/api/queue/current')
                .then(response => response.json())
                .then(data => {
                    updateQueueDisplay(data);
                    
                    // Play sound if new queue added
                    if (data.length > lastQueueCount) {
                        playNotificationSound();
                        showNotification('Pasien baru dalam antrian!');
                    }
                    lastQueueCount = data.length;
                })
                .catch(error => console.error('Error refreshing queue:', error));
        }
        
        function updateQueueDisplay(queueData) {
            const container = document.getElementById('queueContainer');
            container.innerHTML = '';
            
            if (queueData.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada antrian saat ini</h5>
                    </div>
                `;
                return;
            }
            
            queueData.forEach(queue => {
                const card = createQueueCard(queue);
                container.appendChild(card);
            });
        }
        
        function createQueueCard(queue) {
            const col = document.createElement('div');
            col.className = 'col-md-4 mb-3';
            
            col.innerHTML = `
                <div class="card queue-card border-left-${queue.priority ? 'danger' : 'info'}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1">
                                    <span class="badge bg-${queue.priority ? 'danger' : 'primary'} me-2">
                                        ${queue.queue_number}
                                    </span>
                                    ${queue.patient_name}
                                </h5>
                                <p class="card-text mb-1">
                                    <small class="text-muted">
                                        <i class="fas fa-user-md me-1"></i>${queue.doctor_name}
                                    </small>
                                </p>
                                <p class="card-text mb-0">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>${queue.time}
                                    </small>
                                </p>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                        type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="/patients/${queue.patient_id}">
                                            <i class="fas fa-user me-2"></i>Detail Pasien
                                        </a>
                                    </li>
                                    <li>
                                        <form action="/visits/${queue.id}/status" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="PATCH">
                                            <input type="hidden" name="status" value="diperiksa">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-play me-2"></i>Mulai Pemeriksaan
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" 
                                           onclick="cancelQueue(${queue.id})">
                                            <i class="fas fa-times me-2"></i>Batalkan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        ${queue.note ? `
                        <div class="mt-2">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-sticky-note me-1"></i>${queue.note}
                            </span>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            return col;
        }
        
        function searchPatients(query) {
            fetch(`/api/patients/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => console.error('Error searching patients:', error));
        }
        
        function displaySearchResults(patients) {
            quickSearchResults.innerHTML = '';
            
            if (patients.length === 0) {
                quickSearchResults.innerHTML = '<div class="alert alert-info">Tidak ditemukan</div>';
                return;
            }
            
            patients.forEach(patient => {
                const div = document.createElement('div');
                div.className = 'card mb-2';
                div.innerHTML = `
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">${patient.nama}</h6>
                                <small class="text-muted">${patient.no_rekam_medis}</small>
                            </div>
                            <a href="/patients/${patient.id}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                `;
                quickSearchResults.appendChild(div);
            });
        }
        
        function updateQueuePreview() {
            const doctorSelect = document.getElementById('doctorSelect');
            const priorityCheck = document.getElementById('priorityCheck');
            const preview = document.getElementById('queuePreview');
            
            if (doctorSelect.value) {
                preview.classList.remove('d-none');
                document.getElementById('previewDoctorName').textContent = 
                    doctorSelect.options[doctorSelect.selectedIndex].text;
                
                const priorityNote = document.getElementById('previewPriorityNote');
                if (priorityCheck.checked) {
                    priorityNote.classList.remove('d-none');
                    document.getElementById('previewQueueNumber').textContent = 'P001';
                } else {
                    priorityNote.classList.add('d-none');
                    document.getElementById('previewQueueNumber').textContent = 'A001';
                }
                
                document.getElementById('printButton').classList.remove('d-none');
            } else {
                preview.classList.add('d-none');
                document.getElementById('printButton').classList.add('d-none');
            }
        }
        
        function generateQueueNumber() {
            const doctorId = document.getElementById('doctorSelect').value;
            const isPriority = document.getElementById('priorityCheck').checked;
            const note = document.getElementById('queueNote').value;
            
            fetch('/api/queue/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    doctor_id: doctorId,
                    priority: isPriority,
                    note: note
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('previewQueueNumber').textContent = data.queue_number;
                    showNotification('Nomor antrian berhasil digenerate!', 'success');
                    
                    // Update queue display
                    refreshQueue();
                } else {
                    showNotification('Gagal generate antrian: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error generating queue:', error);
                showNotification('Terjadi kesalahan', 'error');
            });
        }
        
        function printQueue() {
            window.print();
        }
        
        function cancelQueue(visitId) {
            if (confirm('Batalkan antrian ini?')) {
                fetch(`/visits/${visitId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: 'batal' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        refreshQueue();
                        showNotification('Antrian berhasil dibatalkan', 'success');
                    }
                })
                .catch(error => console.error('Error cancelling queue:', error));
            }
        }
        
        function playNotificationSound() {
            const audio = new Audio('/sounds/notification.mp3');
            audio.play().catch(e => console.log('Audio play failed:', e));
        }
        
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Cleanup interval on page unload
        window.addEventListener('beforeunload', function() {
            clearInterval(autoRefresh);
        });
    });
</script>
@endsection