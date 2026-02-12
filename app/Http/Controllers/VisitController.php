<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class VisitController extends Controller
{
    public function index(Request $request)
{
    $query = Visit::with(['patient', 'doctor', 'medicalRecord']);
    
    // For doctors, only show their patients
    if (auth()->user()->role == 'dokter') {
        $query->where('doctor_id', auth()->id());
    }
    
    // Apply filters
    if ($request->filled('status')) {
    $query->where('status', $request->status);
}

if ($request->filled('tanggal')) {
    $query->whereDate('tanggal_kunjungan', $request->tanggal);
} elseif (auth()->user()->role == 'dokter') {
    $query->whereDate('tanggal_kunjungan', today());
}

if ($request->filled('search')) {
    $query->whereHas('patient', function($q) use ($request) {
        $q->where('nama', 'like', '%' . $request->search . '%')
          ->orWhere('no_rekam_medis', 'like', '%' . $request->search . '%');
    });
}
 else {
        // For doctors, default to today's visits
        if (auth()->user()->role == 'dokter') {
            $query->whereDate('tanggal_kunjungan', today());
        }
    }
    
    // For admin search
    if ($request->has('search')) {
        $query->whereHas('patient', function($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%')
              ->orWhere('no_rekam_medis', 'like', '%' . $request->search . '%');
        });
    }
    
    // Get visits
    if (auth()->user()->role == 'dokter') {
        $visits = $query->orderByRaw("
                CASE 
                    WHEN status = 'diperiksa' THEN 1
                    WHEN status = 'menunggu' THEN 2
                    WHEN status = 'selesai' THEN 3
                END
            ")
            ->orderBy('created_at', 'asc')
            ->get();
    } else {
        $visits = $query->orderBy('created_at', 'desc')->paginate(10);
    }
    
    $doctors = User::where('role', 'dokter')->get();
    
    return view('visits.index', compact('visits', 'doctors'));
}

    public function create()
{
    // Get all patients (limit to reasonable number, like 100)
    $patients = Patient::orderBy('nama')->limit(100)->get();
    $doctors = User::where('role', 'dokter')->get();
    
    return view('visits.create', compact('patients', 'doctors'));
}

    public function store(Request $request)
{
    if (auth()->user()->role != 'petugas') {
        abort(403, 'Unauthorized');
    }
    $validated = $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'doctor_id' => 'required|exists:users,id',
        'tanggal_kunjungan' => 'required|date',
        'poli' => 'nullable|string|max:50',
        'prioritas' => 'nullable|in:normal,prioritas'
    ]);

    // Get doctor info
    $doctor = User::findOrFail($request->doctor_id);
    
    // Generate queue number
    $lastQueue = Visit::whereDate('tanggal_kunjungan', $request->tanggal_kunjungan)
        ->orderBy('nomor_antrian', 'desc')
        ->first();
    
    $nextQueueNumber = $lastQueue ? $lastQueue->nomor_antrian + 1 : 1;
    
    // Determine prefix based on priority
    $prefix = $request->prioritas == 'prioritas' ? 'P' : 'A';
    
    // For priority patients, you might want a different numbering system
    if ($request->prioritas == 'prioritas') {
        $lastPriority = Visit::whereDate('tanggal_kunjungan', $request->tanggal_kunjungan)
            ->where('prioritas', 'prioritas')
            ->orderBy('nomor_antrian', 'desc')
            ->first();
        
        $nextQueueNumber = $lastPriority ? $lastPriority->nomor_antrian + 1 : 1;
        $prefix = 'P';
    }
    
    $validated['status'] = 'menunggu';
    $validated['nomor_antrian'] = $nextQueueNumber;
    $validated['prefix_antrian'] = $prefix;
    $validated['prioritas'] = $request->prioritas ?? 'normal';
    $validated['poli'] = $request->poli ?? 'Umum';
    
    $visit = Visit::create($validated);

    if (auth()->user()->role != 'petugas') {
        abort(403, 'Unauthorized');
    }
    
    return redirect()->route('visits.index')
        ->with('success', 'Kunjungan berhasil didaftarkan. Nomor Antrian: ' . $visit->nomor_antrian_full);
}

    public function updateStatus(Request $request, Visit $visit)
{
    $request->validate([
        'status' => 'required|in:menunggu,diperiksa,selesai'
    ]);

    // Role validation
    if (!in_array(auth()->user()->role, ['petugas', 'dokter'])) {
        abort(403, 'Unauthorized');
    }

    // Kalau sudah selesai → tidak boleh diubah lagi
    if ($visit->status == 'selesai') {
        return back()->with('error', 'Status yang sudah selesai tidak dapat diubah.');
    }

    // Aturan transisi status
    $allowedTransitions = [
        'menunggu' => ['diperiksa'],
        'diperiksa' => ['selesai'],
    ];

    if (!in_array($request->status, $allowedTransitions[$visit->status] ?? [])) {
        return back()->with('error', 'Perubahan status tidak valid.');
    }

    $visit->update([
        'status' => $request->status,
        'status_updated_at' => now()
    ]);

    // Kalau dokter menyelesaikan → arahkan ke rekam medis
    if ($request->status == 'selesai' && auth()->user()->role == 'dokter') {
        return redirect()->route('medical-records.create', $visit)
            ->with('success', 'Silakan input rekam medis.');
    }

    return back()->with('success', 'Status berhasil diperbarui.');
}


    public function antrian()
    {
        $todayVisits = Visit::with('patient')
            ->whereDate('tanggal_kunjungan', today())
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->get();
            
            $visits = Visit::with(['patient', 'doctor'])
           ->whereDate('tanggal_kunjungan', today())
           ->whereIn('status', ['menunggu', 'diperiksa', 'selesai'])
           ->orderByRaw("
               CASE 
                   WHEN status = 'diperiksa' THEN 1
                   WHEN status = 'menunggu' THEN 2
                   WHEN status = 'selesai' THEN 3
               END
           ")
           ->orderBy('created_at', 'asc')
           ->get();
        return view('visits.antrian', compact('todayVisits', 'visits'));

        
    }

    public function estimateQueue(Request $request)
{
    $date = $request->input('date');
    $doctorId = $request->input('doctor_id');
    $priority = $request->input('priority', false) === 'true';
    
    // Get the count of visits for the selected date and doctor
    $query = Visit::whereDate('tanggal_kunjungan', $date)
        ->where('doctor_id', $doctorId);
    
    if ($priority) {
        // For priority, count only priority visits
        $query->where('prioritas', 'prioritas');
        $visitCount = $query->count();
        $queueNumber = $visitCount + 1;
    } else {
        // For normal, count all visits
        $visitCount = $query->count();
        $queueNumber = $visitCount + 1;
    }
    
    // Estimate wait time (assuming 15 minutes per patient)
    $estimatedWait = $visitCount * 15;
    
    return response()->json([
        'queue_number' => $queueNumber,
        'wait_time' => $estimatedWait
    ]);
}

public function dokterAntrian(Request $request)
{
    $doctorId = auth()->id();
    
    $visits = Visit::with(['patient'])
        ->whereDate('tanggal_kunjungan', today())
        ->where('doctor_id', $doctorId)
        ->whereIn('status', ['menunggu', 'diperiksa', 'selesai'])
        ->orderByRaw("
            CASE 
                WHEN status = 'diperiksa' THEN 1
                WHEN status = 'menunggu' THEN 2
                WHEN status = 'selesai' THEN 3
            END
        ")
        ->orderBy('created_at', 'asc')
        ->get();
        
    return view('visits.dokter-antrian', compact('visits'));
}
}