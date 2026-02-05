<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Visit;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function create(Visit $visit)
    {
        $medicines = Medicine::where('stok', '>', 0)->get();
        
        return view('medical_records.create', compact('visit', 'medicines'));
    }

    public function store(Request $request, Visit $visit)
    {
        DB::beginTransaction();
        
        try {
            $validated = $request->validate([
                'keluhan' => 'required|string',
                'diagnosa' => 'required|string',
                'tindakan' => 'nullable|string',
                'catatan' => 'nullable|string',
                'medicines' => 'nullable|array',
                'medicines.*.id' => 'required|exists:medicines,id',
                'medicines.*.jumlah' => 'required|integer|min:1',
                'medicines.*.aturan_pakai' => 'required|string'
            ]);
            
            // Create medical record
            $medicalRecord = MedicalRecord::create([
                'visit_id' => $visit->id,
                'keluhan' => $validated['keluhan'],
                'diagnosa' => $validated['diagnosa'],
                'tindakan' => $validated['tindakan'] ?? null,
                'catatan' => $validated['catatan'] ?? null
            ]);
            
            // Create prescriptions
            if (isset($validated['medicines'])) {
                foreach ($validated['medicines'] as $medicineData) {
                    $medicine = Medicine::find($medicineData['id']);
                    
                    // Check stock
                    if ($medicine->stok < $medicineData['jumlah']) {
                        throw new \Exception("Stok obat {$medicine->nama_obat} tidak mencukupi");
                    }
                    
                    // Create prescription
                    $medicalRecord->prescriptions()->create([
                        'medicine_id' => $medicineData['id'],
                        'jumlah' => $medicineData['jumlah'],
                        'aturan_pakai' => $medicineData['aturan_pakai']
                    ]);
                    
                    // Reduce stock
                    $medicine->reduceStock($medicineData['jumlah']);
                }
            }
            
            // Update visit status
            $visit->update(['status' => 'selesai']);
            
            DB::commit();
            
            return redirect()->route('visits.index')
                ->with('success', 'Rekam medis berhasil disimpan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan rekam medis: ' . $e->getMessage());
        }
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['visit.patient', 'prescriptions.medicine']);
        
        return view('medical_records.show', compact('medicalRecord'));
    }
}