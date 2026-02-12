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
        // Hanya dokter pemilik kunjungan yang boleh akses
        if (auth()->user()->role === 'dokter' && $visit->doctor_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
        }

        // Cegah double rekam medis
        if ($visit->medicalRecord) {
            return redirect()
                ->route('medical-records.edit', $visit->medicalRecord)
                ->with('info', 'Rekam medis sudah ada. Anda dapat mengeditnya.');
        }

        $medicines = Medicine::where('stok', '>', 0)
            ->orderBy('nama_obat')
            ->get();

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
            'tekanan_darah' => 'nullable|string|max:20',
            'nadi' => 'nullable|string|max:20',
            'suhu' => 'nullable|string|max:20',
            'pernafasan' => 'nullable|string|max:20',
            'pemeriksaan_fisik' => 'nullable|string|max:1000',
            'medicines' => 'nullable|array',
'medicines.*.medicine_id' => 'nullable|exists:medicines,id',
'medicines.*.jumlah' => 'nullable|integer|min:1|required_with:medicines.*.medicine_id',
'medicines.*.aturan_pakai' => 'nullable|string|max:255|required_with:medicines.*.medicine_id',


        ]);

        try {

            DB::transaction(function () use ($validated) {

                $visit = Visit::lockForUpdate()->findOrFail($validated['visit_id']);

                // Pastikan dokter hanya akses visit miliknya
                if (auth()->user()->role === 'dokter' && $visit->doctor_id !== auth()->id()) {
                    abort(403, 'Anda tidak memiliki akses ke kunjungan ini.');
                }

                // Cegah double rekam medis
                if ($visit->medicalRecord) {
                    throw new \Exception('Rekam medis sudah ada untuk kunjungan ini.');
                }

                // Buat medical record
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

                // Jika ada obat yang dipilih
$medicines = collect($validated['medicines'] ?? [])
    ->filter(fn($m) => !empty($m['medicine_id']))
    ->values();

if ($medicines->isNotEmpty()) {

    foreach ($medicines as $medicineData) {

        $medicine = Medicine::lockForUpdate()
            ->findOrFail($medicineData['medicine_id']);

        if ($medicine->stok < $medicineData['jumlah']) {
            throw new \Exception(
                "Stok obat {$medicine->nama_obat} tidak mencukupi. Stok tersedia: {$medicine->stok}"
            );
        }

        Prescription::create([
            'medical_record_id' => $medicalRecord->id,
            'medicine_id' => $medicineData['medicine_id'],
            'jumlah' => $medicineData['jumlah'],
            'aturan_pakai' => $medicineData['aturan_pakai'],
        ]);

        $medicine->decrement('stok', $medicineData['jumlah']);
    }
}


                // Update status kunjungan otomatis
                $visit->update([
                    'status' => 'selesai',
                    'waktu_selesai' => now(),
                ]);
            });

        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('visits.index')
            ->with('success', 'Rekam medis berhasil disimpan dan kunjungan ditandai selesai.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        if (auth()->user()->role === 'dokter' &&
            $medicalRecord->visit->doctor_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke rekam medis ini.');
        }

        $medicalRecord->load(['visit.patient', 'visit.doctor', 'prescriptions.medicine']);

        return view('medical_records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        if (auth()->user()->role === 'dokter' &&
            $medicalRecord->visit->doctor_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke rekam medis ini.');
        }

        $medicines = Medicine::where('stok', '>', 0)
            ->orderBy('nama_obat')
            ->get();

        return view('medical_records.edit', compact('medicalRecord', 'medicines'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        if (auth()->user()->role === 'dokter' &&
            $medicalRecord->visit->doctor_id !== auth()->id()) {
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

        return redirect()
            ->route('medical-records.show', $medicalRecord)
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function index(Request $request)
    {
        $query = MedicalRecord::with(['visit.patient', 'visit.doctor'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('visit.patient', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_rekam_medis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('doctor_id')) {
            $query->whereHas('visit', function ($q) use ($request) {
                $q->where('doctor_id', $request->doctor_id);
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $medicalRecords = $query->paginate(15);

        $totalRecords = MedicalRecord::count();
        $todayRecords = MedicalRecord::whereDate('created_at', today())->count();
        $weekRecords = MedicalRecord::whereBetween(
            'created_at',
            [now()->startOfWeek(), now()->endOfWeek()]
        )->count();

        $activeDoctors = User::where('role', 'dokter')->count();

        $recentRecords = MedicalRecord::with(['visit.patient'])
            ->latest()
            ->limit(5)
            ->get();

        $topDiagnoses = MedicalRecord::select('diagnosa', DB::raw('count(*) as total'))
            ->groupBy('diagnosa')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $recordsByDoctor = User::where('role', 'dokter')
            ->withCount(['visits' => function ($query) {
                $query->has('medicalRecord');
            }])
            ->orderByDesc('visits_count')
            ->limit(5)
            ->get();

        $avgRecordsPerDay = MedicalRecord::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('COUNT(*) / 30 as average'))
            ->value('average') ?? 0;

        $doctors = User::where('role', 'dokter')
            ->orderBy('name')
            ->get();

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
