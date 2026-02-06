<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('nama_layanan')->paginate(10);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nama_layanan' => 'required|string|max:255',
        'kode_layanan' => 'nullable|string|max:50|unique:services,kode_layanan',
        'tarif'        => 'required|numeric|min:0',
        'deskripsi'    => 'nullable|string|max:1000',
    ], [
        'nama_layanan.required' => 'Nama layanan wajib diisi.',
        'nama_layanan.max'      => 'Nama layanan maksimal 255 karakter.',
        'kode_layanan.unique'   => 'Kode layanan sudah digunakan.',
        'tarif.required'        => 'Tarif wajib diisi.',
        'tarif.numeric'         => 'Tarif harus berupa angka.',
        'tarif.min'             => 'Tarif tidak boleh negatif.',
    ]);

    Service::create($validated);

    return redirect()
        ->route('services.index')
        ->with('success', 'Layanan berhasil ditambahkan.');
}


    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kode_layanan' => 'required|string|max:50|unique:services,kode_layanan,' . $service->id,
            'tarif' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string'
        ]);

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Service $service)
    {
        // Check if service is used in transactions
        if ($service->transactionDetails()->exists()) {
            return redirect()->route('services.index')
                ->with('error', 'Layanan tidak dapat dihapus karena sudah digunakan dalam transaksi.');
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}