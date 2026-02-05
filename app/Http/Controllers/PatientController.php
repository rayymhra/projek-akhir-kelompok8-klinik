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
        'no_hp' => 'required|string|max:15',
        'no_hp_keluarga' => 'nullable|string|max:15',
        'email' => 'nullable|email|max:255',
        'nik' => 'nullable|string|max:16|unique:patients,nik',
        'no_bpjs' => 'nullable|string|max:30',
        'rt' => 'nullable|string|max:3',
        'rw' => 'nullable|string|max:3',
        'kelurahan' => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kota' => 'nullable|string|max:100',
        'provinsi' => 'nullable|string|max:100',
        'kode_pos' => 'nullable|string|max:5',
        'golongan_darah' => 'nullable|in:A,B,AB,O',
        'alergi' => 'nullable|string|max:500',
        'riwayat_penyakit' => 'nullable|string',
        'nama_keluarga' => 'nullable|string|max:255',
        'hubungan_keluarga' => 'nullable|string|max:50',
        'pekerjaan' => 'nullable|string|max:100',
        'status_pernikahan' => 'nullable|in:Belum Menikah,Menikah,Cerai',
        'catatan' => 'nullable|string'
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
        'no_hp' => 'required|string|max:15',
        'no_hp_keluarga' => 'nullable|string|max:15',
        'email' => 'nullable|email|max:255',
        'nik' => 'nullable|string|max:16|unique:patients,nik,' . $patient->id,
        'no_bpjs' => 'nullable|string|max:30',
        'rt' => 'nullable|string|max:3',
        'rw' => 'nullable|string|max:3',
        'kelurahan' => 'nullable|string|max:100',
        'kecamatan' => 'nullable|string|max:100',
        'kota' => 'nullable|string|max:100',
        'provinsi' => 'nullable|string|max:100',
        'kode_pos' => 'nullable|string|max:5',
        'golongan_darah' => 'nullable|in:A,B,AB,O',
        'alergi' => 'nullable|string|max:500',
        'riwayat_penyakit' => 'nullable|string',
        'nama_keluarga' => 'nullable|string|max:255',
        'hubungan_keluarga' => 'nullable|string|max:50',
        'pekerjaan' => 'nullable|string|max:100',
        'status_pernikahan' => 'nullable|in:Belum Menikah,Menikah,Cerai',
        'catatan' => 'nullable|string',
        'is_active' => 'nullable|boolean'
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