<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\QueueController;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// AJAX Patient Routes
Route::get('/patients/search', [PatientController::class, 'search'])->name('patients.search');
Route::get('/patients/{patient}/quick-info', [PatientController::class, 'quickInfo'])->name('patients.quick-info');
Route::get('/patients/{patient}/data', [PatientController::class, 'data'])->name('patients.data');


// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Routes based on role
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')->middleware('role:admin');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->name('petugas.dashboard')->middleware('role:petugas');

    Route::get('/dokter/dashboard', [DashboardController::class, 'dokter'])
        ->name('dokter.dashboard')->middleware('role:dokter');

    Route::get('/kasir/dashboard', [DashboardController::class, 'kasir'])
        ->name('kasir.dashboard')->middleware('role:kasir');

    // Patient Routes
    Route::resource('patients', PatientController::class)->middleware('role:admin,petugas');
    
    // Patient AJAX Routes
    Route::get('/patients/search', [PatientController::class, 'search'])
        ->name('patients.search')->middleware('role:admin,petugas');
    
    Route::get('/patients/{patient}/data', [PatientController::class, 'data'])
        ->name('patients.data')->middleware('role:admin,petugas');
    
    Route::get('/patients/{patient}/quick-info', [PatientController::class, 'quickInfo'])
        ->name('patients.quick-info')->middleware('role:admin,petugas');

    // Visit Routes
    Route::resource('visits', VisitController::class)->middleware('role:admin,petugas');
    Route::patch('/visits/{id}/status', [VisitController::class, 'updateStatus'])
    ->name('visits.updateStatus');

    Route::get('/antrian', [VisitController::class, 'antrian'])->name('dokter.antrian')->middleware('role:dokter');
    
    // Queue Estimation API
    Route::get('/api/visits/estimate-queue', [VisitController::class, 'estimateQueue'])
        ->name('visits.estimate-queue')->middleware('role:admin,petugas');

    // Medical Record Routes
    Route::get('/visits/{visit}/medical-record/create', [MedicalRecordController::class, 'create'])
        ->name('medical-records.create')->middleware('role:dokter');
    Route::post('/visits/{visit}/medical-record', [MedicalRecordController::class, 'store'])
        ->name('medical-records.store')->middleware('role:dokter');
    Route::get('/medical-records/{medicalRecord}', [MedicalRecordController::class, 'show'])
        ->name('medical-records.show');

        // Medicine Special Routes FIRST
Route::get('/medicines/low-stock', [MedicineController::class, 'lowStock'])
    ->name('medicines.low-stock')->middleware('role:admin,dokter');

Route::get('/medicines/expired-soon', [MedicineController::class, 'expiredSoon'])
    ->name('medicines.expired-soon')->middleware('role:admin,dokter');

Route::get('/medicines/{medicine}/stock-history', [MedicineController::class, 'stockHistory'])
    ->name('medicines.stock-history')->middleware('role:admin,dokter');

    // Medicine Routes
    Route::resource('medicines', MedicineController::class)->middleware('role:admin,dokter');
    Route::get('/medicines/low-stock', [MedicineController::class, 'lowStock'])
        ->name('medicines.low-stock')->middleware('role:admin,dokter');
    Route::get('/medicines/expired-soon', [MedicineController::class, 'expiredSoon'])
        ->name('medicines.expired-soon')->middleware('role:admin,dokter');
    Route::get('/medicines/{medicine}/stock-history', [MedicineController::class, 'stockHistory'])
        ->name('medicines.stock-history')->middleware('role:admin,dokter');

    // Transaction Routes
    Route::resource('transactions', TransactionController::class)->middleware('role:kasir');
    Route::post('/transactions/{transaction}/confirm', [TransactionController::class, 'confirmPayment'])
        ->name('transactions.confirm')->middleware('role:kasir');

    // User Management Routes (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::put('/users/{user}/deactivate', [UserController::class, 'deactivate'])
            ->name('users.deactivate');
    });

    // Report Routes
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index')->middleware('role:admin');
    Route::get('/reports/export/{type}', [ReportController::class, 'export'])
        ->name('reports.export')->middleware('role:admin');

    // API Routes for AJAX calls
    Route::prefix('api')->group(function () {
        // Dashboard Stats
        Route::get('/dashboard/admin-stats', [DashboardController::class, 'adminStats']);
        Route::get('/dashboard/petugas-stats', [DashboardController::class, 'petugasStats']);
        Route::get('/dashboard/dokter-stats', [DashboardController::class, 'dokterStats']);
        Route::get('/dashboard/kasir-stats', [DashboardController::class, 'kasirStats']);
        
        // Queue Management
        Route::get('/queue/current', [QueueController::class, 'currentQueue']);
        Route::post('/queue/generate', [QueueController::class, 'generateNumber']);
        Route::post('/queue/call-next', [QueueController::class, 'callNext']);
        Route::get('/queue/next-patient', [QueueController::class, 'nextPatient']);
        
        // Doctor Availability
        Route::post('/doctor/availability', [DashboardController::class, 'updateAvailability']);
        Route::get('/doctor/schedule', [DashboardController::class, 'doctorSchedule']);
        
        // Patient Search (additional endpoints)
        Route::get('/patients/list', [PatientController::class, 'list']);
        Route::get('/patients/{patient}/visits/unpaid', [PatientController::class, 'unpaidVisits']);
        
        // Medicine Management
        Route::get('/medicines/available', [DashboardController::class, 'availableMedicines']);
        
        // Transaction Processing
        Route::get('/transactions/pending', [TransactionController::class, 'pendingTransactions']);
        Route::post('/transactions/{transaction}/process', [TransactionController::class, 'processPayment']);
        Route::post('/transactions/quick-pay', [TransactionController::class, 'quickPayment']);
        
        // Prescriptions
        Route::post('/prescriptions/create', [DashboardController::class, 'createPrescription']);
    });

    // Home Redirect based on role
    Route::get('/', function () {
        $user = auth()->user();

        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'petugas':
                return redirect()->route('petugas.dashboard');
            case 'dokter':
                return redirect()->route('dokter.dashboard');
            case 'kasir':
                return redirect()->route('kasir.dashboard');
            default:
                return redirect('/login');
        }
    });
});