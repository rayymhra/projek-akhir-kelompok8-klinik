<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['visit.patient', 'visit.doctor', 'details']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->has('search')) {
            $query->whereHas('visit.patient', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                    ->orWhere('no_rekam_medis', 'like', '%'.$request->search.'%');
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Transaction::count(),
            'pending' => Transaction::where('status', 'menunggu')->count(),
            'completed' => Transaction::where('status', 'lunas')->count(),
            'cancelled' => Transaction::where('status', 'batal')->count(),
            'today' => Transaction::whereDate('created_at', now()->toDateString())->count(),
            'today_amount' => Transaction::whereDate('created_at', now()->toDateString())
                ->where('status', 'lunas')
                ->sum('total_biaya'),
        ];

        return view('transactions.index', compact('transactions', 'stats'));

    }

    /**
     * Langkah 1: Pilih Kunjungan
     */
    public function step1(Request $request)
    {
        $query = Visit::with(['patient', 'doctor'])
            ->whereDoesntHave('transaction')
            ->where('status', 'selesai')
            ->orderBy('tanggal_kunjungan', 'desc');

        if ($request->has('search')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->search.'%')
                    ->orWhere('no_rekam_medis', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->has('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->has('date')) {
            $query->whereDate('tanggal_kunjungan', $request->date);
        }

        $visits = $query->paginate(15);
        $doctors = \App\Models\User::where('role', 'dokter')->orderBy('name')->get();

        return view('transactions.step1', compact('visits', 'doctors'));
    }

    /**
     * Langkah 2: Tambah Item Transaksi
     */
    public function step2(Visit $visit)
    {
        if ($visit->transaction) {
            return redirect()->route('transactions.show', $visit->transaction)
                ->with('info', 'Transaksi untuk kunjungan ini sudah ada.');
        }

        $visit->load(['patient', 'doctor', 'medicalRecord.prescriptions.medicine']);

        $medicines = Medicine::where('stok', '>', 0)->orderBy('nama_obat')->get();
        $services = Service::orderBy('nama_layanan')->get();

        $cartItems = Session::get("cart_{$visit->id}", []);

        return view('transactions.step2', compact('visit', 'medicines', 'services', 'cartItems'));
    }

    /**
     * Tambah item ke cart
     */
    public function addToCart(Request $request, Visit $visit)
    {
        $validated = $request->validate([
            'type' => 'required|in:medicine,service,other',
            'item_id' => 'nullable|required_if:type,medicine,service',
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        $cartItems = Session::get("cart_{$visit->id}", []);
        $itemKey = uniqid();

        $cartItems[$itemKey] = [
            'type' => $validated['type'],
            'item_id' => $validated['item_id'] ?? null,
            'name' => $validated['name'],
            'quantity' => $validated['quantity'],
            'price' => $validated['price'],
            'note' => $validated['note'] ?? null,
            'subtotal' => $validated['quantity'] * $validated['price'],
        ];

        Session::put("cart_{$visit->id}", $cartItems);

        return response()->json([
            'success' => true,
            'total_items' => count($cartItems),
            'total_amount' => collect($cartItems)->sum('subtotal'),
        ]);
    }

    /**
     * Hapus item dari cart
     */
    public function removeFromCart(Request $request, Visit $visit)
    {
        $cartItems = Session::get("cart_{$visit->id}", []);

        if (isset($cartItems[$request->item_key])) {
            unset($cartItems[$request->item_key]);
            Session::put("cart_{$visit->id}", $cartItems);
        }

        return response()->json([
            'success' => true,
            'total_items' => count($cartItems),
            'total_amount' => collect($cartItems)->sum('subtotal'),
        ]);
    }

    /**
     * Tambahkan resep ke cart
     */
    public function addPrescriptionsToCart(Request $request, Visit $visit)
    {
        $prescriptionIds = $request->input('prescription_ids', []);

        if (empty($prescriptionIds)) {
            return response()->json(['success' => false, 'message' => 'Tidak ada resep yang dipilih']);
        }

        $visit->load('medicalRecord.prescriptions.medicine');
        $cartItems = Session::get("cart_{$visit->id}", []);

        foreach ($prescriptionIds as $prescriptionId) {
            $prescription = $visit->medicalRecord->prescriptions->firstWhere('id', $prescriptionId);

            if ($prescription && $prescription->medicine) {
                $itemKey = uniqid();
                $cartItems[$itemKey] = [
                    'type' => 'medicine',
                    'item_id' => $prescription->medicine->id,
                    'name' => $prescription->medicine->nama_obat,
                    'quantity' => $prescription->jumlah,
                    'price' => $prescription->medicine->harga,
                    'note' => $prescription->aturan_pakai,
                    'subtotal' => $prescription->jumlah * $prescription->medicine->harga,
                ];
            }
        }

        Session::put("cart_{$visit->id}", $cartItems);

        return response()->json([
            'success' => true,
            'total_items' => count($cartItems),
            'total_amount' => collect($cartItems)->sum('subtotal'),
        ]);
    }

    /**
     * Langkah 3: Pembayaran
     */
    public function step3(Visit $visit)
    {
        $cartItems = Session::get("cart_{$visit->id}", []);

        if (empty($cartItems)) {
            return redirect()->route('transactions.step2', $visit)
                ->with('error', 'Tambahkan item terlebih dahulu sebelum melanjutkan ke pembayaran.');
        }

        $totalAmount = collect($cartItems)->sum('subtotal');

        return view('transactions.step3', compact('visit', 'cartItems', 'totalAmount'));
    }

    /**
     * Simpan transaksi
     */
    public function store(Request $request, Visit $visit)
    {
        $cartItems = Session::get("cart_{$visit->id}", []);

        if (empty($cartItems)) {
            return redirect()->route('transactions.step2', $visit)
                ->with('error', 'Tidak ada item dalam transaksi.');
        }

        $validated = $request->validate([
            'metode_pembayaran' => 'required|in:tunai,transfer,qris,e-wallet',
            'status' => 'required|in:menunggu,lunas,batal',
            'bukti_pembayaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek apakah sudah ada transaksi
        if ($visit->transaction) {
            return redirect()->route('transactions.show', $visit->transaction)
                ->with('info', 'Transaksi untuk kunjungan ini sudah ada.');
        }

        DB::beginTransaction();
        try {
            // Hitung total
            $totalAmount = collect($cartItems)->sum('subtotal');

            // Handle bukti pembayaran
            $paymentProofPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $paymentProofPath = $request->file('bukti_pembayaran')->store('payment-proofs', 'public');
            }

            // Buat transaksi dengan created_by
            $transaction = Transaction::create([
                'visit_id' => $visit->id,
                'total_biaya' => $totalAmount,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status' => $validated['status'],
                'bukti_pembayaran' => $paymentProofPath,
                'created_by' => auth()->id(),
            ]);

            // Buat detail transaksi
            foreach ($cartItems as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'item_type' => $item['type'],
                    'item_id' => $item['item_id'] ?? null,
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'note' => $item['note'] ?? null,
                ]);

                // Kurangi stok obat
                if ($item['type'] === 'medicine' && $item['item_id']) {
                    $medicine = Medicine::find($item['item_id']);
                    if ($medicine) {
                        $medicine->decrement('stok', $item['quantity']);
                    }
                }
            }

            // Hapus session cart
            Session::forget("cart_{$visit->id}");

            DB::commit();

            // Redirect dengan pilihan
            if ($request->has('print_invoice')) {
                return redirect()->route('transactions.invoice', $transaction)
                    ->with('success', 'Transaksi berhasil dibuat.');
            }

            return redirect()->route('transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil dibuat.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal membuat transaksi: '.$e->getMessage());
        }
    }

    /**
     * Tampilkan detail transaksi dengan user tracking
     */
    public function show(Transaction $transaction)
    {
        $transaction->load([
            'visit.patient',
            'visit.doctor',
            'details',
            'createdBy',
            'confirmedBy',
            'cancelledBy',
        ]);

        return view('transactions.show', compact('transaction'));
    }

    public function invoice(Transaction $transaction)
    {
        $transaction->load(['visit.patient', 'visit.doctor', 'details']);

        return view('transactions.invoice', compact('transaction'));
    }

    public function clearCart(Visit $visit)
    {
        Session::forget("cart_{$visit->id}");

        return redirect()->route('transactions.step2', $visit)
            ->with('info', 'Keranjang transaksi telah dikosongkan.');
    }

    /**
     * Hapus transaksi (soft delete / hard delete)
     */
    public function destroy(Transaction $transaction)
    {
        if ($transaction->status === 'lunas') {
            return back()->with('error', 'Transaksi yang sudah lunas tidak dapat dihapus.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok obat jika belum dibatalkan
            if ($transaction->status !== 'batal') {
                foreach ($transaction->details as $detail) {
                    if ($detail->item_type === 'medicine' && $detail->item_id) {
                        $medicine = Medicine::find($detail->item_id);
                        if ($medicine) {
                            $medicine->increment('stok', $detail->quantity);
                        }
                    }
                }
            }

            // Hapus file bukti pembayaran
            if ($transaction->bukti_pembayaran) {
                Storage::disk('public')->delete($transaction->bukti_pembayaran);
            }

            // Hapus transaksi
            $transaction->delete();

            DB::commit();

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transaksi berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi pembayaran
     */
    public function confirm(Request $request, Transaction $transaction)
    {
        if ($transaction->status !== 'menunggu') {
            return back()->with('error', 'Transaksi tidak dapat dikonfirmasi.');
        }

        DB::beginTransaction();
        try {
            // Validasi file bukti pembayaran jika diupload
            if ($request->hasFile('bukti_pembayaran')) {
                $request->validate([
                    'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                ]);
                
                // Hapus bukti pembayaran lama jika ada
                if ($transaction->bukti_pembayaran) {
                    Storage::disk('public')->delete($transaction->bukti_pembayaran);
                }
                
                // Upload bukti pembayaran baru
                $path = $request->file('bukti_pembayaran')->store('payment-proofs', 'public');
                $transaction->bukti_pembayaran = $path;
            }

            // Update status dan data konfirmasi
            $transaction->update([
                'status' => 'lunas',
                'confirmed_by' => auth()->id(),
                'confirmed_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('transactions.show', $transaction)
                ->with('success', 'Pembayaran berhasil dikonfirmasi. Transaksi telah lunas.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengkonfirmasi pembayaran: ' . $e->getMessage());
        }
    }

     /**
     * Batalkan transaksi
     */
    public function cancel(Request $request, Transaction $transaction)
    {
        if ($transaction->status === 'lunas') {
            return back()->with('error', 'Transaksi yang sudah lunas tidak dapat dibatalkan.');
        }

        if ($transaction->status === 'batal') {
            return back()->with('error', 'Transaksi sudah dibatalkan sebelumnya.');
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok obat
            foreach ($transaction->details as $detail) {
                if ($detail->item_type === 'medicine' && $detail->item_id) {
                    $medicine = Medicine::find($detail->item_id);
                    if ($medicine) {
                        $medicine->increment('stok', $detail->quantity);
                    }
                }
            }

            // Update status dan data pembatalan
            $transaction->update([
                'status' => 'batal',
                'cancelled_by' => auth()->id(),
                'cancelled_at' => now(),
            ]);

            DB::commit();

            return redirect()
                ->route('transactions.show', $transaction)
                ->with('success', 'Transaksi berhasil dibatalkan. Stok obat telah dikembalikan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan transaksi: ' . $e->getMessage());
        }
    }
}
