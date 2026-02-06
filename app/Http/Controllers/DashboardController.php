<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Visit;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Transaction;
use App\Models\MedicalRecord;

class DashboardController extends Controller
{
    public function admin()
{
    $monthlyIncome = Transaction::whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->where('status', 'lunas')
        ->sum('total_biaya');

    $lowStockMedicines = Medicine::where('stok', '<=', 10)->count();

     $recentVisits = Visit::with(['patient', 'doctor'])
        ->latest()
        ->take(5)
        ->get();

        $userStats = [
    'admin'   => User::where('role', 'admin')->count(),
    'petugas' => User::where('role', 'petugas')->count(),
    'dokter'  => User::where('role', 'dokter')->count(),
    'kasir'   => User::where('role', 'kasir')->count(),
];


    return view('dashboard.admin', [
        'totalPatients'     => Patient::count(),
        'totalVisits'       => Visit::count(),
        'totalMedicines'    => Medicine::count(),
        'totalUsers'        => User::count(),
        'todayVisits'       => Visit::whereDate('created_at', Carbon::today())->count(),
        'monthlyIncome'     => $monthlyIncome,
        'lowStockMedicines' => $lowStockMedicines,
        'recentVisits'      => $recentVisits,
        'userStats'         => $userStats,
    ]);
}


    public function petugas()
{
    $today = Carbon::today();
    $now = Carbon::now();

    $stats = [

        // 🔹 CARD STATS
        'todayVisits' => Visit::whereDate('created_at', $today)->count(),

        'waitingVisits' => Visit::where('status', 'menunggu')->count(),

        'newPatientsToday' => Patient::whereDate('created_at', $today)->count(),

        'totalPatients' => Patient::count(),

        'monthlyVisits' => Visit::whereMonth('created_at', $now->month)
                                ->whereYear('created_at', $now->year)
                                ->count(),

        // 🔹 CURRENT QUEUE (Antrian Saat Ini)
        'currentQueue' => Visit::with(['patient', 'doctor'])
            ->where('status', 'menunggu')
            ->orderBy('created_at')
            ->get()
            ->map(function ($visit, $index) {
                return [
                    'id' => $visit->id,
                    'queue_number' => 'A' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'patient_name' => $visit->patient->nama ?? '-',
                    'patient_id' => $visit->patient->id ?? null,
                    'doctor_name' => $visit->doctor->name ?? 'Belum ditentukan',
                    'time' => $visit->created_at->format('H:i'),
                    'priority' => $visit->priority ?? false,
                    'note' => $visit->note,
                ];
            }),

        // 🔹 TABLE — PASIEN BARU
        'recentPatients' => Patient::whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get(),

        // 🔹 TABLE — KUNJUNGAN HARI INI
        'todaysVisits' => Visit::with(['patient', 'transaction'])
            ->whereDate('created_at', $today)
            ->latest()
            ->take(10)
            ->get(),

        // 🔹 DOCTOR LIST (Modal Cetak Antrian)
        'doctors' => collect(), // empty collection so view doesn't error

    ];

    return view('dashboard.petugas', compact('stats'));
}



  public function dokter()
{
    $doctorId = auth()->id();
    
    // Get today's visits for this doctor
    $todayVisits = Visit::with(['patient', 'medicalRecord'])
        ->where('doctor_id', $doctorId)
        ->whereDate('tanggal_kunjungan', today())
        ->orderByRaw("
            CASE 
                WHEN status = 'diperiksa' THEN 1
                WHEN status = 'menunggu' THEN 2
                WHEN status = 'selesai' THEN 3
            END
        ")
        ->orderBy('created_at', 'asc')
        ->get();
        
    // Get recent medical records (last 7 days)
    $recentRecords = MedicalRecord::whereHas('visit', function($query) use ($doctorId) {
        $query->where('doctor_id', $doctorId)
              ->whereDate('created_at', '>=', now()->subDays(7));
    })
    ->with(['visit.patient'])
    ->orderBy('created_at', 'desc')
    ->get();
    
    // Calculate statistics
    $stats = [
        'waitingPatients' => $todayVisits->where('status', 'menunggu')->count(),
        'completedToday' => $todayVisits->where('status', 'selesai')->count(),
        'todayAppointments' => $todayVisits->count(),
        'avgRecords' => round($recentRecords->count() / 7, 1),
    ];
    
    return view('dashboard.dokter', compact('todayVisits', 'recentRecords', 'stats'));
}


    public function kasir()
{
    $today = Carbon::today();

    $todayTransactions = Transaction::whereDate('created_at', $today)->count();

    $todayIncome = Transaction::whereDate('created_at', $today)
        ->where('status', 'lunas')
        ->sum('total_biaya');

    $pendingTransactions = Transaction::where('status', 'menunggu')->count();

    $avgTransaction = Transaction::whereDate('created_at', $today)
        ->where('status', 'lunas')
        ->avg('total_biaya') ?? 0;

    $pendingPayments = Transaction::with('visit.patient')
        ->where('status', 'menunggu')
        ->latest()
        ->take(5)
        ->get();

    $recentTransactions = Transaction::with('visit.patient')
        ->latest()
        ->take(5)
        ->get();

    // Payment method stats
    $cashAmount = Transaction::whereDate('created_at', $today)->where('metode_pembayaran', 'tunai')->sum('total_biaya');
    $transferAmount = Transaction::whereDate('created_at', $today)->where('metode_pembayaran', 'transfer')->sum('total_biaya');
    $qrisAmount = Transaction::whereDate('created_at', $today)->where('metode_pembayaran', 'qris')->sum('total_biaya');
    $ewalletAmount = Transaction::whereDate('created_at', $today)->where('metode_pembayaran', 'e-wallet')->sum('total_biaya');

    $stats = compact(
        'todayTransactions',
        'todayIncome',
        'pendingTransactions',
        'avgTransaction',
        'pendingPayments',
        'recentTransactions',
        'cashAmount',
        'transferAmount',
        'qrisAmount',
        'ewalletAmount'
    );

    return view('dashboard.kasir', compact('stats'));
}


    
}
