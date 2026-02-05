<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        $query = Visit::with(['patient', 'doctor']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('tanggal')) {
            $query->whereDate('tanggal_kunjungan', $request->tanggal);
        }
        
        $visits = $query->orderBy('created_at', 'desc')->paginate(10);
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
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'tanggal_kunjungan' => 'required|date'
        ]);

        $validated['status'] = 'menunggu';
        
        Visit::create($validated);
        
        return redirect()->route('visits.index')
            ->with('success', 'Kunjungan berhasil didaftarkan.');
    }

    public function updateStatus(Request $request, Visit $visit)
    {
        // Validate the status
        $request->validate([
            'status' => 'required|in:menunggu,diperiksa,selesai'
        ]);
        
        // Update the status
        $visit->update([
            'status' => $request->status,
            'status_updated_at' => now() // optional: add this to your migration
        ]);
        
        // If status changed to 'selesai' and user is a doctor, redirect to medical record
        if ($request->status == 'selesai' && auth()->user()->role == 'dokter') {
            return redirect()->route('medical-records.create', $visit)
                ->with('success', 'Status kunjungan diubah ke selesai. Silakan input pemeriksaan.');
        }
        
        // For other status changes, show success message
        $statusMessages = [
            'menunggu' => 'Kunjungan ditandai sebagai menunggu.',
            'diperiksa' => 'Kunjungan ditandai sebagai sedang diperiksa.',
            'selesai' => 'Kunjungan ditandai sebagai selesai.'
        ];
        
        return back()->with('success', $statusMessages[$request->status]);
    }

    public function antrian()
    {
        $todayVisits = Visit::with('patient')
            ->whereDate('tanggal_kunjungan', today())
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('visits.antrian', compact('todayVisits'));
    }

    public function estimateQueue(Request $request)
{
    $date = $request->input('date');
    $doctorId = $request->input('doctor_id');
    $priority = $request->input('priority', false);
    
    // Get the count of visits for the selected date and doctor
    $visitCount = Visit::whereDate('tanggal_kunjungan', $date)
        ->where('doctor_id', $doctorId)
        ->count();
    
    // Calculate queue number (next in line)
    $queueNumber = $visitCount + 1;
    
    // If priority, adjust queue logic (you can customize this)
    if ($priority) {
        // Priority patients might get special queue numbers
        $priorityCount = Visit::whereDate('tanggal_kunjungan', $date)
            ->where('doctor_id', $doctorId)
            ->where('prioritas', true)
            ->count();
        
        $queueNumber = 'P-' . ($priorityCount + 1);
    }
    
    // Estimate wait time (assuming 15 minutes per patient)
    $estimatedWait = $visitCount * 15;
    
    return response()->json([
        'queue_number' => $queueNumber,
        'wait_time' => $estimatedWait
    ]);
}
}