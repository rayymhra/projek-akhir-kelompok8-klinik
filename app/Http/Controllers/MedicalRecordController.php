<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visit;
use App\Models\Medicine;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    public function create(Visit $visit)
    {
        // Verify that the visit belongs to the doctor
        if (auth()->user()->role == 'dokter' && $visit->doctor_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
        }
        
        // Check if medical record already exists
        if ($visit->medicalRecord) {
            return redirect()->route('medical-records.edit', $visit->medicalRecord)
                ->with('info', 'Rekam medis sudah ada. Anda dapat mengeditnya.');
        }
        
        $medicines = Medicine::where('stok', '>', 0)->orderBy('nama_obat')->get();
        
        return view('medical_records.create', compact('visit', 'medicines'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
        'visit_id' => 'required|exists:visits,id',
        'keluhan' => 'required|string|max:1000',
        'diagnosa' => 'required|string|max:500',
        'tindakan' => 'nullable|string|max:1000',
        'catatan' => 'nullable|string|max:1000',
        
        // New fields
        'tekanan_darah' => 'nullable|string|max:20',
        'nadi' => 'nullable|string|max:20',
        'suhu' => 'nullable|string|max:20',
        'pernafasan' => 'nullable|string|max:20',
        'pemeriksaan_fisik' => 'nullable|string|max:1000',
        
        'medicines' => 'nullable|array',
        'medicines.*.medicine_id' => 'required_with:medicines|exists:medicines,id',
        'medicines.*.jumlah' => 'required_with:medicines|integer|min:1',
        'medicines.*.aturan_pakai' => 'required_with:medicines|string|max:255',
    ]);
    
    // Create medical record with all fields
    $medicalRecord = MedicalRecord::create([
        'visit_id' => $validated['visit_id'],
        'keluhan' => $validated['keluhan'],
        'diagnosa' => $validated['diagnosa'],
        'tindakan' => $validated['tindakan'] ?? null,
        'catatan' => $validated['catatan'] ?? null,
        'tekanan_darah' => $validated['tekanan_darah'] ?? null,
        'nadi' => $validated['nadi'] ?? null,
        'suhu' => $validated['suhu'] ?? null,
        'pernafasan' => $validated['pernafasan'] ?? null,
        'pemeriksaan_fisik' => $validated['pemeriksaan_fisik'] ?? null,
    ]);
    
        
        // Create prescriptions if any
        if (!empty($validated['medicines'])) {
            foreach ($validated['medicines'] as $medicineData) {
                // Check stock availability
                $medicine = Medicine::findOrFail($medicineData['medicine_id']);
                
                if ($medicine->stok < $medicineData['jumlah']) {
                    // Delete medical record if stock insufficient
                    $medicalRecord->delete();
                    return back()->with('error', "Stok obat {$medicine->nama_obat} tidak mencukupi. Stok tersedia: {$medicine->stok}");
                }
                
                // Create prescription
                Prescription::create([
                    'medical_record_id' => $medicalRecord->id,
                    'medicine_id' => $medicineData['medicine_id'],
                    'jumlah' => $medicineData['jumlah'],
                    'aturan_pakai' => $medicineData['aturan_pakai'],
                ]);
                
                // Update medicine stock
                $medicine->decrement('stok', $medicineData['jumlah']);
            }
        }
        
        // Update visit status to 'selesai'
        $visit = Visit::findOrFail($validated['visit_id']);
        $visit->update([
            'status' => 'selesai',
            'waktu_selesai' => now(),
        ]);
        
        return redirect()->route('visits.index')
            ->with('success', 'Rekam medis berhasil disimpan dan kunjungan ditandai selesai.');
    }
    
    public function show(MedicalRecord $medicalRecord)
    {
        // Verify access
        if (auth()->user()->role == 'dokter' && $medicalRecord->visit->doctor_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke rekam medis ini.');
        }
        
        $medicalRecord->load(['visit.patient', 'visit.doctor', 'prescriptions.medicine']);
        
        return view('medical_records.show', compact('medicalRecord'));
    }
    
    public function edit(MedicalRecord $medicalRecord)
    {
        // Verify access
        if (auth()->user()->role == 'dokter' && $medicalRecord->visit->doctor_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke rekam medis ini.');
        }
        
        $medicines = Medicine::where('stok', '>', 0)->orderBy('nama_obat')->get();
        
        return view('medical_records.edit', compact('medicalRecord', 'medicines'));
    }
    
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        // Verify access
        if (auth()->user()->role == 'dokter' && $medicalRecord->visit->doctor_id != auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke rekam medis ini.');
        }
        
        $validated = $request->validate([
            'keluhan' => 'required|string|max:1000',
            'diagnosa' => 'required|string|max:500',
            'tindakan' => 'nullable|string|max:1000',
            'catatan' => 'nullable|string|max:1000',
            'tekanan_darah' => 'nullable|string|max:20',
            'nadi' => 'nullable|string|max:20',
            'suhu' => 'nullable|string|max:20',
            'pernafasan' => 'nullable|string|max:20',
            'pemeriksaan_fisik' => 'nullable|string|max:1000',
        ]);
        
        $medicalRecord->update($validated);
        
        return redirect()->route('medical-records.show', $medicalRecord)
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }
    
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['visit.patient', 'visit.doctor'])
            ->orderBy('created_at', 'desc');
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('visit.patient', function($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('no_rekam_medis', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by doctor
        if ($request->has('doctor_id')) {
            $query->whereHas('visit', function($q) use ($request) {
                $q->where('doctor_id', $request->doctor_id);
            });
        }
        
        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }
        
        $medicalRecords = $query->paginate(15);
        
        // Statistics
        $totalRecords = MedicalRecord::count();
        $todayRecords = MedicalRecord::whereDate('created_at', today())->count();
        $weekRecords = MedicalRecord::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $activeDoctors = User::where('role', 'dokter')->count();
        
        // Recent records for quick access
        $recentRecords = MedicalRecord::with(['visit.patient'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Top diagnoses
        $topDiagnoses = MedicalRecord::select('diagnosa', DB::raw('count(*) as total'))
            ->groupBy('diagnosa')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();
        
        // Records by doctor
        $recordsByDoctor = User::where('role', 'dokter')
            ->withCount(['visits' => function($query) {
                $query->has('medicalRecord');
            }])
            ->orderBy('visits_count', 'desc')
            ->limit(5)
            ->get();
        
        // Average records per day (last 30 days)
        $avgRecordsPerDay = MedicalRecord::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('COUNT(*) / 30 as average'))
            ->value('average') ?? 0;
        
        $doctors = User::where('role', 'dokter')->orderBy('name')->get();
        
        return view('medical_records.index', compact(
            'medicalRecords',
            'totalRecords',
            'todayRecords',
            'weekRecords',
            'activeDoctors',
            'recentRecords',
            'topDiagnoses',
            'recordsByDoctor',
            'avgRecordsPerDay',
            'doctors'
        ));
    }
}