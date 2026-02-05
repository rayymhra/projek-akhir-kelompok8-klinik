<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_obat', 'like', "%{$search}%")
                ->orWhere('kode_obat', 'like', "%{$search}%");
        }

        if ($request->has('jenis')) {
            $query->where('jenis_obat', $request->jenis);
        }

        if ($request->has('stock')) {
            if ($request->stock == 'low') {
                $query->where('stok', '<=', 10);
            } elseif ($request->stock == 'out') {
                $query->where('stok', 0);
            }
        }

        $medicines = $query->orderBy('nama_obat')->paginate(15);

        $medicineTypes = Medicine::select('jenis_obat')
            ->distinct()
            ->pluck('jenis_obat');

        // ✅ ADD THIS PART HERE (before return)
        $totalStock = Medicine::sum('stok');
        $totalValue = Medicine::sum(DB::raw('stok * harga'));
        $lowStockCount = Medicine::where('stok', '<=', 10)->count();

        return view('medicines.index', compact(
            'medicines',
            'medicineTypes',
            'totalStock',
            'totalValue',
            'lowStockCount'
        ));
    }

    public function create()
{
    $medicineTypes = [
        'Tablet' => 'Tablet',
        'Kapsul' => 'Kapsul',
        'Sirup' => 'Sirup',
        'Salep' => 'Salep',
        'Injeksi' => 'Injeksi',
        'Krim' => 'Krim',
        'Drops' => 'Drops',
        'Inhaler' => 'Inhaler',
        'Lainnya' => 'Lainnya'
    ];
    
    $satuanOptions = [
        'Tablet' => 'Tablet',
        'Kapsul' => 'Kapsul',
        'Botol' => 'Botol',
        'Tube' => 'Tube',
        'Ampul' => 'Ampul',
        'Vial' => 'Vial',
        'Strip' => 'Strip',
        'Sachet' => 'Sachet',
        'Pcs' => 'Pcs'
    ];
    
    return view('medicines.create', compact('medicineTypes', 'satuanOptions'));
}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kode_obat' => 'required|string|unique:medicines',
            'jenis_obat' => 'required|string',
            'satuan' => 'required|string',
            'supplier' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:255',
            'lokasi_penyimpanan' => 'nullable|string',
            'golongan' => 'nullable|string',
            'kategori' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $medicine = Medicine::create($validated);

        StockHistory::create([
            'medicine_id' => $medicine->id,
            'user_id' => auth()->id(),
            'type' => 'masuk',
            'quantity' => $medicine->stok,
            'stock_after' => $medicine->stok,
            'note' => 'Initial stock',
        ]);

        return redirect()->route('medicines.index')
            ->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function edit(Medicine $medicine)
{
    $medicineTypes = [
        'Tablet' => 'Tablet',
        'Kapsul' => 'Kapsul',
        'Sirup' => 'Sirup',
        'Salep' => 'Salep',
        'Injeksi' => 'Injeksi',
        'Krim' => 'Krim',
        'Drops' => 'Drops',
        'Inhaler' => 'Inhaler',
        'Lainnya' => 'Lainnya'
    ];
    
    $satuanOptions = [
        'Tablet' => 'Tablet',
        'Kapsul' => 'Kapsul',
        'Botol' => 'Botol',
        'Tube' => 'Tube',
        'Ampul' => 'Ampul',
        'Vial' => 'Vial',
        'Strip' => 'Strip',
        'Sachet' => 'Sachet',
        'Pcs' => 'Pcs'
    ];
    
    return view('medicines.edit', compact('medicine', 'medicineTypes', 'satuanOptions'));
}

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'nama_obat' => 'required|string|max:255',
            'kode_obat' => 'required|string|unique:medicines,kode_obat,'.$medicine->id,
            'jenis_obat' => 'required|string',
            'satuan' => 'required|string',
            'supplier' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:255',
            'lokasi_penyimpanan' => 'nullable|string',
            'golongan' => 'nullable|string',
            'kategori' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer|min:0',
            'stok_minimum' => 'nullable|integer|min:1',
            'harga' => 'required|numeric|min:0',
            'expired_date' => 'nullable|date',
        ]);

        $oldStock = $medicine->stok;
        $medicine->update($validated);
        $newStock = $medicine->stok;

        if ($oldStock != $newStock) {
            $type = $newStock > $oldStock ? 'masuk' : 'keluar';
            $qty = abs($newStock - $oldStock);

            StockHistory::create([
                'medicine_id' => $medicine->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantity' => $qty,
                'stock_after' => $newStock,
                'note' => 'Stock updated via edit form',
            ]);
        }

        return redirect()->route('medicines.index')
            ->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Medicine $medicine)
    {
        // Check if medicine has prescriptions
        if ($medicine->prescriptions()->count() > 0) {
            return back()->with('error', 'Obat tidak dapat dihapus karena telah digunakan dalam resep.');
        }

        $medicine->delete();

        return redirect()->route('medicines.index')
            ->with('success', 'Data obat berhasil dihapus.');
    }

    public function stockHistory(Medicine $medicine)
    {
        $stockHistories = $medicine->stockHistories()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('medicines.stock-history', compact('medicine', 'stockHistories'));
    }

    public function lowStock()
    {
        $lowStockMedicines = Medicine::where('stok', '<=', 10)
            ->orderBy('stok')
            ->get();

        return view('medicines.low-stock', compact('lowStockMedicines'));
    }

    public function expiredSoon()
    {
        $thirtyDaysFromNow = now()->addDays(30);
        $expiredSoonMedicines = Medicine::whereNotNull('expired_date')
            ->where('expired_date', '<=', $thirtyDaysFromNow)
            ->where('expired_date', '>', now())
            ->orderBy('expired_date')
            ->get();

        return view('medicines.expired-soon', compact('expiredSoonMedicines'));
    }
}
