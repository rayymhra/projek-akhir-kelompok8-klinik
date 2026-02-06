<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MedicalRecordController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', action: [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PUBLIC QUEUE PAGE
|--------------------------------------------------------------------------
*/
Route::get('/antrian', [VisitController::class, 'antrian'])->name('visits.antrian');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PER ROLE
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')->middleware('role:admin');

    Route::get('/petugas/dashboard', [DashboardController::class, 'petugas'])
        ->name('petugas.dashboard')->middleware('role:petugas');

    Route::get('/dokter/dashboard', [DashboardController::class, 'dokter'])
        ->name('dokter.dashboard')->middleware('role:dokter');

    Route::get('/kasir/dashboard', [DashboardController::class, 'kasir'])
        ->name('kasir.dashboard')->middleware('role:kasir');


    /*
|--------------------------------------------------------------------------
| PATIENT ROUTES (FIXED ORDER)
|--------------------------------------------------------------------------
| Doctor can VIEW
| Only admin & petugas can MANAGE
*/

// View list
Route::get('/patients', [PatientController::class, 'index'])
    ->name('patients.index')
    ->middleware('role:admin,petugas,dokter');

// CREATE (must be BEFORE {patient})
Route::get('/patients/create', [PatientController::class, 'create'])
    ->name('patients.create')
    ->middleware('role:admin,petugas');

Route::post('/patients', [PatientController::class, 'store'])
    ->name('patients.store')
    ->middleware('role:admin,petugas');

// SHOW (dynamic)
Route::get('/patients/{patient}', [PatientController::class, 'show'])
    ->name('patients.show')
    ->middleware('role:admin,petugas,dokter');

// EDIT
Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])
    ->name('patients.edit')
    ->middleware('role:admin,petugas');

Route::put('/patients/{patient}', [PatientController::class, 'update'])
    ->name('patients.update')
    ->middleware('role:admin,petugas');

// DELETE
Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])
    ->name('patients.destroy')
    ->middleware('role:admin');



    /*
    |--------------------------------------------------------------------------
    | VISITS (QUEUE & EXAMINATION)
    |--------------------------------------------------------------------------
    */
    Route::resource('visits', VisitController::class)
        ->middleware('role:admin,petugas,dokter');

    Route::patch('/visits/{visit}/update-status', [VisitController::class, 'updateStatus'])
        ->name('visits.updateStatus')->middleware('role:admin,petugas,dokter');


    /*
    |--------------------------------------------------------------------------
    | MEDICAL RECORDS
    |--------------------------------------------------------------------------
    */
    Route::prefix('medical-records')->group(function () {

        Route::get('/', [MedicalRecordController::class, 'index'])
            ->name('medical-records.index')->middleware('role:admin,dokter');

        Route::get('/create/{visit}', [MedicalRecordController::class, 'create'])
            ->name('medical-records.create')->middleware('role:dokter');

        Route::post('/', [MedicalRecordController::class, 'store'])
            ->name('medical-records.store')->middleware('role:dokter');

        Route::get('/{medicalRecord}', [MedicalRecordController::class, 'show'])
            ->name('medical-records.show')->middleware('role:admin,dokter');

        Route::get('/{medicalRecord}/edit', [MedicalRecordController::class, 'edit'])
            ->name('medical-records.edit')->middleware('role:dokter');

        Route::put('/{medicalRecord}', [MedicalRecordController::class, 'update'])
            ->name('medical-records.update')->middleware('role:dokter');
    });


    /*
    |--------------------------------------------------------------------------
    | MEDICINES
    |--------------------------------------------------------------------------
    */
    Route::get('/medicines/low-stock', [MedicineController::class, 'lowStock'])
        ->name('medicines.low-stock')->middleware('role:admin,dokter');

    Route::get('/medicines/expired-soon', [MedicineController::class, 'expiredSoon'])
        ->name('medicines.expired-soon')->middleware('role:admin,dokter');

    Route::get('/medicines/{medicine}/stock-history', [MedicineController::class, 'stockHistory'])
        ->name('medicines.stock-history')->middleware('role:admin,dokter');

    Route::resource('medicines', MedicineController::class)
        ->middleware('role:admin,dokter');


    /*
    |--------------------------------------------------------------------------
    | TRANSACTIONS (KASIR ONLY)
    |--------------------------------------------------------------------------
    */
    // Route::resource('transactions', TransactionController::class)
    //     ->middleware('role:kasir');

    Route::post('/transactions/{transaction}/confirm', [TransactionController::class, 'confirmPayment'])
        ->name('transactions.confirm')->middleware('role:kasir');

    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'printInvoice'])
    ->name('transactions.print')->middleware('role:kasir');


Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/create/step1', [TransactionController::class, 'step1'])->name('step1');
    Route::get('/create/step2/{visit}', [TransactionController::class, 'step2'])->name('step2');
    Route::get('/create/step3/{visit}', [TransactionController::class, 'step3'])->name('step3');
    Route::post('/cart/{visit}/add', [TransactionController::class, 'addToCart'])->name('addToCart');
    Route::post('/cart/{visit}/remove', [TransactionController::class, 'removeFromCart'])->name('removeFromCart');
    Route::post('/cart/{visit}/add-prescriptions', [TransactionController::class, 'addPrescriptionsToCart'])->name('addPrescriptionsToCart');
    Route::get('/cart/{visit}/clear', [TransactionController::class, 'clearCart'])->name('clearCart');
    Route::post('/store/{visit}', [TransactionController::class, 'store'])->name('store');
    Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
    Route::get('/{transaction}/invoice', [TransactionController::class, 'invoice'])->name('invoice');
});

Route::post('/transactions/{transaction}/cancel', 
    [TransactionController::class, 'cancel'])
    ->name('transactions.cancel')
    ->middleware('role:kasir');

    Route::delete('/transactions/{transaction}', 
    [TransactionController::class, 'destroy'])
    ->name('transactions.destroy')
    ->middleware('role:kasir');

    Route::post(
    '/transactions/{transaction}/confirm',
    [TransactionController::class, 'confirm']
)->name('transactions.confirm');





    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT (ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::put('/users/{user}/deactivate', [UserController::class, 'deactivate'])
            ->name('users.deactivate');
    });


    /*
    |--------------------------------------------------------------------------
    | REPORTS (ADMIN)
    |--------------------------------------------------------------------------
    */
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index')->middleware('role:admin');

    Route::get('/reports/export/{type}', [ReportController::class, 'export'])
        ->name('reports.export')->middleware('role:admin');


    /*
    |--------------------------------------------------------------------------
    | HOME REDIRECT
    |--------------------------------------------------------------------------
    */
    Route::get('/', function () {
        $user = auth()->user();

        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('visits.index'),
            'dokter'  => redirect()->route('visits.index'),
            'kasir'   => redirect()->route('transactions.index'),
            default   => redirect('/login'),
        };
    });
});

Route::get('/visits/search', function(Request $request) {
    $query = $request->get('q');
    
    $visits = Visit::with(['patient', 'doctor'])
        ->whereHas('patient', function($q) use ($query) {
            $q->where('nama', 'like', '%' . $query . '%')
              ->orWhere('no_rekam_medis', 'like', '%' . $query . '%');
        })
        ->whereDoesntHave('transaction') // Only visits without transactions
        ->where('status', 'selesai') // Only completed visits
        ->limit(10)
        ->get()
        ->map(function($visit) {
            return [
                'id' => $visit->id,
                'patient_nama' => $visit->patient->nama,
                'patient_no_rekam_medis' => $visit->patient->no_rekam_medis,
                'doctor_name' => $visit->doctor->name,
                'tanggal_kunjungan' => $visit->tanggal_kunjungan->format('d/m/Y'),
                'status' => $visit->status,
            ];
        });
    
    return response()->json($visits);
});

// routes/web.php
Route::resource('services', ServiceController::class)->except(['show']);