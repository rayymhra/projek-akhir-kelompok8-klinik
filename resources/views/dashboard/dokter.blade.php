@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">
                <i class="fas fa-user-md me-2"></i>Dashboard Dokter
                <small class="text-muted">Dr. {{ auth()->user()->name }}</small>
            </h1>
            <div class="btn-group">
                <button class="btn btn-primary" onclick="toggleAvailability()" id="availabilityBtn">
                    <i class="fas fa-circle me-2" id="availabilityIcon"></i>
                    <span id="availabilityText">Sedia</span>
                </button>
                <a href="{{ route('dokter.antrian') }}" class="btn btn-success">
                    <i class="fas fa-list-ol me-2"></i>Lihat Antrian
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Dokter -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Pasien Menunggu
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['waitingPatients'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-warning">
                                <i class="fas fa-clock"></i> Dalam antrian
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-clock fa-2x text-gray-300"></i>
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
                            Diperiksa Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completedToday'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-success">
                                <i class="fas fa-check-circle"></i> Selesai
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-stethoscope fa-2x text-gray-300"></i>
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
                            Total Janji Temu
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['todayAppointments'] }}</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-info">
                                <i class="fas fa-calendar-alt"></i> Hari ini
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

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Rata-rata Waktu Periksa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['avgConsultationTime'] ?? '15' }}m</div>
                        <div class="mt-2 mb-0 text-muted text-xs">
                            <span class="text-warning">
                                <i class="fas fa-stopwatch"></i> Per pasien
                            </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jadwal Hari Ini -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calendar-day me-2"></i>Jadwal Konsultasi Hari Ini
                </h6>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshSchedule()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAppointmentModal">
                        <i class="fas fa-plus me-1"></i> Tambah Janji
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Pasien</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="scheduleTable">
                            @foreach($stats['todaysVisits'] as $visit)
                            <tr class="{{ $visit->status == 'menunggu' ? 'table-warning' : ($visit->status == 'diperiksa' ? 'table-info' : 'table-success') }}">
                                <td>
                                    <div class="font-weight-bold">{{ $visit->created_at->format('H:i') }}</div>
                                    <small class="text-muted">{{ $visit->queue_number ?? '-' }}</small>
                                </td>
                                <td>
                                    <div class="font-weight-bold">{{ $visit->patient->nama }}</div>
                                    <small class="text-muted">
                                        {{ $visit->patient->umur }} thn • 
                                        {{ $visit->patient->jenis_kelamin == 'L' ? 'L' : 'P' }}
                                    </small>
                                </td>
                                <td>
                                    <small>{{ $visit->keluhan_utama ?? 'Belum ada keluhan' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-status-{{ $visit->status }}">
                                        {{ ucfirst($visit->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($visit->status == 'menunggu')
                                        <a href="{{ route('medical-records.create', $visit) }}" 
                                           class="btn btn-success" title="Mulai Periksa">
                                            <i class="fas fa-play"></i>
                                        </a>
                                        @elseif($visit->status == 'diperiksa')
                                        <a href="{{ route('medical-records.create', $visit) }}" 
                                           class="btn btn-primary" title="Lanjutkan">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @elseif($visit->status == 'selesai')
                                        <a href="{{ route('medical-records.show', $visit->medicalRecord) }}" 
                                           class="btn btn-info" title="Lihat Rekam Medis">
                                            <i class="fas fa-file-medical"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('patients.show', $visit->patient) }}" 
                                           class="btn btn-secondary" title="Profil Pasien">
                                            <i class="fas fa-user"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(empty($stats['todaysVisits']))
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Tidak ada jadwal hari ini</h5>
                    <p class="text-muted">Anda dapat menambah janji temu baru</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Panel Cepat Dokter -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-tools me-2"></i>Panel Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2 mb-4">
                    <button class="btn btn-primary btn-lg" onclick="callNextPatient()">
                        <i class="fas fa-bullhorn me-2"></i>Panggil Pasien Berikutnya
                    </button>
                    <a href="{{ route('medicines.index') }}" class="btn btn-info btn-lg">
                        <i class="fas fa-pills me-2"></i>Cek Stok Obat
                    </a>
                    <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#quickPrescriptionModal">
                        <i class="fas fa-prescription me-2"></i>Resep Cepat
                    </button>
                    <a href="{{ route('reports.index', ['type' => 'visits', 'doctor_id' => auth()->id()]) }}" 
                       class="btn btn-success btn-lg">
                        <i class="fas fa-chart-bar me-2"></i>Statistik Saya
                    </a>
                </div>
                
                <!-- Pasien Berikutnya -->
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user-clock me-2"></i>Pasien Berikutnya
                        </h6>
                        <div id="nextPatientInfo">
                            @if($stats['nextPatient'])
                            <div class="text-center">
                                <h4 class="text-danger">{{ $stats['nextPatient']->queue_number }}</h4>
                                <h5>{{ $stats['nextPatient']->patient->nama }}</h5>
                                <p class="text-muted mb-1">
                                    {{ $stats['nextPatient']->patient->umur }} tahun • 
                                    {{ $stats['nextPatient']->patient->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    Menunggu {{ $stats['nextPatient']->waiting_time }} menit
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('medical-records.create', $stats['nextPatient']) }}" 
                                       class="btn btn-success">
                                        <i class="fas fa-play me-2"></i>Mulai Periksa
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                                <h6 class="text-success">Tidak ada antrian</h6>
                                <p class="text-muted">Semua pasien telah dilayani</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Konsultasi -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history me-2"></i>Riwayat Konsultasi Terakhir
                </h6>
                <a href="{{ route('reports.index', ['type' => 'visits', 'doctor_id' => auth()->id()]) }}" 
                   class="btn btn-sm btn-primary">
                    <i class="fas fa-list me-1"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pasien</th>
                                <th>Diagnosa</th>
                                <th>Tindakan</th>
                                <th>Resep</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats['recentConsultations'] as $record)
                            <tr>
                                <td>{{ $record->created_at->format('d/m/Y') }}</td>
                                <td>{{ $record->visit->patient->nama }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $record->diagnosa }}</span>
                                </td>
                                <td>{{ $record->tindakan ?: '-' }}</td>
                                <td>
                                    @if($record->prescriptions->count() > 0)
                                    <span class="badge bg-success">{{ $record->prescriptions->count() }} obat</span>
                                    @else
                                    <span class="badge bg-secondary">Tidak ada</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada riwayat konsultasi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Janji Temu -->
<div class="modal fade" id="addAppointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Janji Temu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addAppointmentForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Pilih Pasien</label>
                        <select class="form-select" id="patientSelect" required>
                            <option value="">Pilih Pasien...</option>
                            <!-- Options akan diisi via AJAX -->
                        </select>
                        <div class="form-text">
                            <a href="{{ route('patients.create') }}" target="_blank">Tambah pasien baru</a>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal & Waktu</label>
                        <input type="datetime-local" class="form-control" id="appointmentDateTime" required 
                               min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keluhan Utama</label>
                        <textarea class="form-control" id="complaint" rows="3" placeholder="Keluhan pasien..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prioritas</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="emergencyCheck">
                            <label class="form-check-label" for="emergencyCheck">
                                <i class="fas fa-exclamation-triangle text-danger me-1"></i>
                                Darurat/Prioritas
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-calendar-plus me-2"></i>Buat Janji
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Resep Cepat -->
<div class="modal fade" id="quickPrescriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-prescription me-2"></i>Resep Cepat
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickPrescriptionForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Pasien</label>
                            <select class="form-select" id="prescriptionPatient" required>
                                <option value="">Pilih Pasien...</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih Obat</label>
                        <div class="input-group mb-2">
                            <select class="form-select" id="medicineSelect">
                                <option value="">Cari obat...</option>
                            </select>
                            <button type="button" class="btn btn-outline-primary" onclick="addMedicine()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <div id="medicineList" class="mt-3">
                            <!-- Daftar obat akan ditambahkan di sini -->
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Instruksi Umum</label>
                        <textarea class="form-control" id="generalInstructions" rows="2" 
                                  placeholder="Instruksi untuk semua obat..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="savePrescription()">
                    <i class="fas fa-save me-2"></i>Simpan Resep
                </button>
                <button type="button" class="btn btn-success" onclick="printPrescription()">
                    <i class="fas fa-print me-2"></i>Cetak Resep
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .availability-online {
        color: #1cc88a;
        animation: pulse-green 2s infinite;
    }
    
    .availability-offline {
        color: #e74a3b;
    }
    
    @keyframes pulse-green {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    .next-patient-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .consultation-history-item {
        border-left: 4px solid #4e73df;
        padding-left: 15px;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    
    .consultation-history-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isAvailable = true;
        let nextPatientInterval;
        
        // Initialize availability button
        updateAvailabilityButton();
        
        // Load patient list for appointment modal
        loadPatientList();
        
        // Load medicine list for prescription modal
        loadMedicineList();
        
        // Auto-refresh schedule every 30 seconds
        setInterval(refreshSchedule, 30000);
        
        // Auto-call next patient if available
        startNextPatientChecker();
        
        function toggleAvailability() {
            isAvailable = !isAvailable;
            updateAvailabilityButton();
            
            // Send status to server
            fetch('/api/doctor/availability', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ available: isAvailable })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(
                        isAvailable ? 'Status: Sedia' : 'Status: Tidak Sedia', 
                        isAvailable ? 'success' : 'warning'
                    );
                }
            });
        }
        
        function updateAvailabilityButton() {
            const btn = document.getElementById('availabilityBtn');
            const icon = document.getElementById('availabilityIcon');
            const text = document.getElementById('availabilityText');
            
            if (isAvailable) {
                btn.className = 'btn btn-success';
                icon.className = 'fas fa-circle availability-online me-2';
                text.textContent = 'Sedia';
            } else {
                btn.className = 'btn btn-danger';
                icon.className = 'fas fa-circle availability-offline me-2';
                text.textContent = 'Tidak Sedia';
            }
        }
        
        function callNextPatient() {
            fetch('/api/queue/call-next', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.patient) {
                    // Play announcement sound
                    playAnnouncementSound();
                    
                    // Show notification
                    showNotification(`Memanggil: ${data.patient.name} - No. ${data.queue_number}`, 'info');
                    
                    // Update UI
                    updateNextPatientInfo(data.patient);
                    refreshSchedule();
                } else {
                    showNotification('Tidak ada pasien dalam antrian', 'warning');
                }
            });
        }
        
        function playAnnouncementSound() {
            const audio = new Audio('/sounds/announcement.mp3');
            audio.play().catch(e => console.log('Audio play failed:', e));
        }
        
        function refreshSchedule() {
            fetch('/api/doctor/schedule')
                .then(response => response.json())
                .then(data => {
                    updateScheduleTable(data);
                })
                .catch(error => console.error('Error refreshing schedule:', error));
        }
        
        function updateScheduleTable(scheduleData) {
            const table = document.getElementById('scheduleTable');
            table.innerHTML = '';
            
            scheduleData.forEach(visit => {
                const row = document.createElement('tr');
                row.className = visit.status == 'menunggu' ? 'table-warning' : 
                               (visit.status == 'diperiksa' ? 'table-info' : 'table-success');
                
                row.innerHTML = `
                    <td>
                        <div class="font-weight-bold">${visit.time}</div>
                        <small class="text-muted">${visit.queue_number || '-'}</small>
                    </td>
                    <td>
                        <div class="font-weight-bold">${visit.patient_name}</div>
                        <small class="text-muted">
                            ${visit.patient_age} thn • ${visit.patient_gender}
                        </small>
                    </td>
                    <td>
                        <small>${visit.complaint || 'Belum ada keluhan'}</small>
                    </td>
                    <td>
                        <span class="badge badge-status-${visit.status}">
                            ${visit.status.charAt(0).toUpperCase() + visit.status.slice(1)}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            ${visit.status == 'menunggu' ? 
                                `<a href="/visits/${visit.id}/medical-record/create" class="btn btn-success" title="Mulai Periksa">
                                    <i class="fas fa-play"></i>
                                </a>` : 
                             visit.status == 'diperiksa' ?
                                `<a href="/visits/${visit.id}/medical-record/create" class="btn btn-primary" title="Lanjutkan">
                                    <i class="fas fa-edit"></i>
                                </a>` :
                                `<a href="/medical-records/${visit.record_id}" class="btn btn-info" title="Lihat Rekam Medis">
                                    <i class="fas fa-file-medical"></i>
                                </a>`
                            }
                            <a href="/patients/${visit.patient_id}" class="btn btn-secondary" title="Profil Pasien">
                                <i class="fas fa-user"></i>
                            </a>
                        </div>
                    </td>
                `;
                
                table.appendChild(row);
            });
        }
        
        function loadPatientList() {
            fetch('/api/patients/list')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('patientSelect');
                    select.innerHTML = '<option value="">Pilih Pasien...</option>';
                    
                    data.forEach(patient => {
                        const option = document.createElement('option');
                        option.value = patient.id;
                        option.textContent = `${patient.nama} (${patient.no_rekam_medis})`;
                        select.appendChild(option);
                    });
                });
        }
        
        function loadMedicineList() {
            fetch('/api/medicines/available')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('medicineSelect');
                    select.innerHTML = '<option value="">Cari obat...</option>';
                    
                    data.forEach(medicine => {
                        const option = document.createElement('option');
                        option.value = medicine.id;
                        option.textContent = `${medicine.nama_obat} (${medicine.kode_obat}) - Stok: ${medicine.stok}`;
                        select.setAttribute('data-medicine', JSON.stringify(medicine));
                        select.appendChild(option);
                    });
                });
        }
        
        function addMedicine() {
            const select = document.getElementById('medicineSelect');
            const selectedOption = select.options[select.selectedIndex];
            
            if (!selectedOption.value) {
                showNotification('Pilih obat terlebih dahulu', 'warning');
                return;
            }
            
            const medicine = JSON.parse(selectedOption.getAttribute('data-medicine'));
            const list = document.getElementById('medicineList');
            
            const medicineItem = document.createElement('div');
            medicineItem.className = 'card mb-2';
            medicineItem.innerHTML = `
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <h6 class="mb-0">${medicine.nama_obat}</h6>
                            <small class="text-muted">${medicine.kode_obat}</small>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control form-control-sm" 
                                   value="1" min="1" max="${medicine.stok}" 
                                   onchange="updateMedicineQuantity(this, ${medicine.harga})">
                        </div>
                        <div class="col-md-3">
                            <textarea class="form-control form-control-sm" rows="1" 
                                      placeholder="Aturan pakai..."></textarea>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-sm btn-danger" 
                                    onclick="this.parentElement.parentElement.parentElement.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="medicine_id" value="${medicine.id}">
                </div>
            `;
            
            list.appendChild(medicineItem);
        }
        
        function updateMedicineQuantity(input, price) {
            // Update subtotal logic here
        }
        
        function savePrescription() {
            const patientId = document.getElementById('prescriptionPatient').value;
            if (!patientId) {
                showNotification('Pilih pasien terlebih dahulu', 'warning');
                return;
            }
            
            // Collect medicine data
            const medicines = [];
            document.querySelectorAll('#medicineList .card').forEach(card => {
                const medicineId = card.querySelector('input[name="medicine_id"]').value;
                const quantity = card.querySelector('input[type="number"]').value;
                const instructions = card.querySelector('textarea').value;
                
                medicines.push({
                    medicine_id: medicineId,
                    quantity: quantity,
                    instructions: instructions
                });
            });
            
            if (medicines.length === 0) {
                showNotification('Tambahkan minimal satu obat', 'warning');
                return;
            }
            
            const data = {
                patient_id: patientId,
                medicines: medicines,
                general_instructions: document.getElementById('generalInstructions').value
            };
            
            fetch('/api/prescriptions/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Resep berhasil disimpan', 'success');
                    // Reset form
                    document.getElementById('medicineList').innerHTML = '';
                    document.getElementById('generalInstructions').value = '';
                }
            });
        }
        
        function printPrescription() {
            // Implement print functionality
            window.print();
        }
        
        function startNextPatientChecker() {
            nextPatientInterval = setInterval(checkNextPatient, 10000); // Check every 10 seconds
        }
        
        function checkNextPatient() {
            if (isAvailable) {
                fetch('/api/queue/next-patient')
                    .then(response => response.json())
                    .then(data => {
                        if (data.patient) {
                            updateNextPatientInfo(data.patient);
                        }
                    });
            }
        }
        
        function updateNextPatientInfo(patient) {
            const container = document.getElementById('nextPatientInfo');
            
            if (patient) {
                container.innerHTML = `
                    <div class="text-center">
                        <h4 class="text-danger">${patient.queue_number}</h4>
                        <h5>${patient.name}</h5>
                        <p class="text-muted mb-1">
                            ${patient.age} tahun • ${patient.gender}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-1"></i>
                            Menunggu ${patient.waiting_time} menit
                        </p>
                        <div class="mt-3">
                            <button class="btn btn-success" onclick="startConsultation(${patient.visit_id})">
                                <i class="fas fa-play me-2"></i>Mulai Periksa
                            </button>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="text-center py-3">
                        <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                        <h6 class="text-success">Tidak ada antrian</h6>
                        <p class="text-muted">Semua pasien telah dilayani</p>
                    </div>
                `;
            }
        }
        
        function startConsultation(visitId) {
            window.location.href = `/visits/${visitId}/medical-record/create`;
        }
        
        function showNotification(message, type = 'info') {
            // Create and show notification
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Cleanup intervals on page unload
        window.addEventListener('beforeunload', function() {
            clearInterval(nextPatientInterval);
        });
    });
</script>
@endsection