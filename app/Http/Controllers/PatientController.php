<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_rekam_medis', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
        }
        
        $patients = $query->paginate(10);
        
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15'
        ]);

        $patient = Patient::create($validated);
        
        return redirect()->route('patients.show', $patient)
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function show(Patient $patient)
    {
        $visits = Visit::where('patient_id', $patient->id)
            ->with(['doctor', 'medicalRecord'])
            ->orderBy('created_at', 'desc')
            ->paginate(5); 
            
        return view('patients.show', compact('patient', 'visits'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15'
        ]);

        $patient->update($validated);
        
        return redirect()->route('patients.show', $patient)
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        
        return redirect()->route('patients.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }

public function search(Request $request)
{
    $q = $request->q;

    $patients = Patient::where('nama', 'like', "%$q%")
        ->orWhere('no_rekam_medis', 'like', "%$q%")
        ->orWhere('no_hp', 'like', "%$q%")
        ->withCount('visits as total_kunjungan')
        ->limit(10)
        ->get();

    return response()->json($patients);
}

public function quickInfo(Patient $patient)
{
    $html = view('patients.partials.quick-info', compact('patient'))->render();

    return response()->json(['html' => $html]);
}

public function data(Patient $patient)
{
    $patient->total_kunjungan = $patient->visits()->count();
    return response()->json($patient);
}

}