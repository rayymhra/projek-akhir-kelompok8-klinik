<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QueueController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'priority' => 'in:normal,prioritas',
            'note' => 'nullable|string|max:255',
            'patient_id' => 'nullable|exists:patients,id'
        ]);

        DB::beginTransaction();
        
        try {
            $doctor = User::findOrFail($request->doctor_id);
            
            // Determine prefix based on priority
            $prefix = $request->priority == 'prioritas' ? 'P' : 'A';
            
            // Get last queue number for today with same priority
            $lastQueue = Visit::whereDate('tanggal_kunjungan', Carbon::today())
                ->where('prefix_antrian', $prefix)
                ->orderBy('nomor_antrian', 'desc')
                ->first();
            
            if ($lastQueue && $lastQueue->nomor_antrian) {
                $queueNumber = $lastQueue->nomor_antrian + 1;
            } else {
                $queueNumber = 1;
            }
            
            // Format queue display number
            $queueDisplay = $prefix . str_pad($queueNumber, 3, '0', STR_PAD_LEFT);
            
            // Use existing patient or create dummy for walk-in
            if ($request->patient_id) {
                $patient = Patient::findOrFail($request->patient_id);
            } else {
                // Create dummy patient for walk-in
                $patient = Patient::create([
                    'nama' => 'PASIEN UMUM',
                    'tanggal_lahir' => Carbon::now()->subYears(30)->format('Y-m-d'),
                    'jenis_kelamin' => 'L',
                    'alamat' => 'PASIEN UMUM',
                    'no_hp' => '081234567890',
                    'no_rekam_medis' => 'UMUM-' . Carbon::now()->format('Ymd') . '-' . $queueDisplay
                ]);
            }
            
            // Create visit for queue
            $visit = Visit::create([
                'patient_id' => $patient->id,
                'doctor_id' => $request->doctor_id,
                'tanggal_kunjungan' => Carbon::now(),
                'status' => 'menunggu',
                'nomor_antrian' => $queueNumber,
                'prefix_antrian' => $prefix,
                'poli' => $request->poli ?? 'Umum',
                'prioritas' => $request->priority ?? 'normal',
                'queue_note' => $request->note
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'queue_number' => $queueDisplay,
                'visit_id' => $visit->id,
                'print_url' => route('queue.print', ['id' => $visit->id])
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate antrian: ' . $e->getMessage()
            ], 500);
        }
    }

    public function print($id)
    {
        $visit = Visit::with(['doctor', 'patient'])
            ->findOrFail($id);
        
        // Format queue number
        $visit->queue_display = $visit->prefix_antrian . str_pad($visit->nomor_antrian, 3, '0', STR_PAD_LEFT);
        
        return view('queue.print', compact('visit'));
    }

    public function today()
    {
        $todayVisits = Visit::with(['doctor', 'patient'])
            ->whereDate('tanggal_kunjungan', Carbon::today())
            ->where('status', 'menunggu')
            ->orderByRaw("FIELD(prioritas, 'prioritas', 'normal')")
            ->orderBy('nomor_antrian')
            ->get()
            ->map(function($visit) {
                $visit->queue_display = $visit->prefix_antrian . str_pad($visit->nomor_antrian, 3, '0', STR_PAD_LEFT);
                return $visit;
            });
            
        return response()->json($todayVisits);
    }

    public function current()
    {
        $queue = Visit::with(['doctor', 'patient'])
            ->whereDate('tanggal_kunjungan', Carbon::today())
            ->where('status', 'menunggu')
            ->orderByRaw("FIELD(prioritas, 'prioritas', 'normal')")
            ->orderBy('nomor_antrian')
            ->get()
            ->map(function($visit) {
                return [
                    'id' => $visit->id,
                    'queue_number' => $visit->prefix_antrian . str_pad($visit->nomor_antrian, 3, '0', STR_PAD_LEFT),
                    'patient_id' => $visit->patient_id,
                    'patient_name' => $visit->patient->nama,
                    'doctor_id' => $visit->doctor_id,
                    'doctor_name' => $visit->doctor->name,
                    'time' => $visit->created_at->format('H:i'),
                    'priority' => $visit->prioritas == 'prioritas',
                    'priority_text' => $visit->prioritas,
                    'note' => $visit->queue_note,
                    'poli' => $visit->poli
                ];
            });
            
        return response()->json($queue);
    }

    public function callNext(Request $request)
    {
        $request->validate([
            'doctor_id' => 'nullable|exists:users,id'
        ]);

        $query = Visit::whereDate('tanggal_kunjungan', Carbon::today())
            ->where('status', 'menunggu')
            ->orderByRaw("FIELD(prioritas, 'prioritas', 'normal')")
            ->orderBy('nomor_antrian');
            
        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        $nextVisit = $query->first();
        
        if ($nextVisit) {
            $nextVisit->update([
                'status' => 'diperiksa',
                'waktu_dipanggil' => Carbon::now()
            ]);
            
            return response()->json([
                'success' => true,
                'queue_number' => $nextVisit->prefix_antrian . str_pad($nextVisit->nomor_antrian, 3, '0', STR_PAD_LEFT),
                'patient_name' => $nextVisit->patient->nama,
                'doctor_name' => $nextVisit->doctor->name,
                'visit_id' => $nextVisit->id
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Tidak ada antrian'
        ], 404);
    }

    public function complete($id)
    {
        $visit = Visit::findOrFail($id);
        
        $visit->update([
            'status' => 'selesai',
            'waktu_selesai' => Carbon::now()
        ]);
        
        return response()->json(['success' => true]);
    }

    public function cancel($id)
    {
        $visit = Visit::findOrFail($id);
        
        $visit->update([
            'status' => 'batal',
            'waktu_selesai' => Carbon::now()
        ]);
        
        return response()->json(['success' => true]);
    }
}