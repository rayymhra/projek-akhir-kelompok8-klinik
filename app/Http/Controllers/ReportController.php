<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use App\Models\Patient;
use App\Models\Transaction;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['start_date', 'end_date', 'type', 'doctor_id']);
        
        // Default to current month
        if (!$request->has('start_date')) {
            $filter['start_date'] = now()->startOfMonth()->format('Y-m-d');
            $filter['end_date'] = now()->endOfMonth()->format('Y-m-d');
        }
        
        $reports = [];
        
        switch ($request->get('type', 'visits')) {
            case 'visits':
                $reports = $this->getVisitReport($filter);
                break;
            case 'transactions':
                $reports = $this->getTransactionReport($filter);
                break;
            case 'patients':
                $reports = $this->getPatientReport($filter);
                break;
            case 'medicines':
                $reports = $this->getMedicineReport($filter);
                break;
            case 'income':
                $reports = $this->getIncomeReport($filter);
                break;
        }
        
        return view('reports.index', compact('reports', 'filter'));
    }

    private function getVisitReport($filter)
    {
        $query = Visit::with(['patient', 'doctor'])
            ->whereBetween('tanggal_kunjungan', [$filter['start_date'], $filter['end_date']]);
            
        if (!empty($filter['doctor_id'])) {
            $query->where('doctor_id', $filter['doctor_id']);
        }
        
        $visits = $query->get();
        
        // Statistics
        $stats = [
            'total' => $visits->count(),
            'menunggu' => $visits->where('status', 'menunggu')->count(),
            'diperiksa' => $visits->where('status', 'diperiksa')->count(),
            'selesai' => $visits->where('status', 'selesai')->count(),
        ];
        
        // Group by date
        $grouped = $visits->groupBy(function($visit) {
            return $visit->tanggal_kunjungan->format('Y-m-d');
        })->map(function($group) {
            return [
                'total' => $group->count(),
                'menunggu' => $group->where('status', 'menunggu')->count(),
                'diperiksa' => $group->where('status', 'diperiksa')->count(),
                'selesai' => $group->where('status', 'selesai')->count(),
            ];
        });
        
        return [
            'type' => 'visits',
            'data' => $visits,
            'stats' => $stats,
            'grouped' => $grouped,
            'title' => 'Laporan Kunjungan'
        ];
    }

    private function getTransactionReport($filter)
    {
        $query = Transaction::with(['visit.patient', 'details'])
            ->whereBetween('created_at', [$filter['start_date'], $filter['end_date']]);
            
        $transactions = $query->get();
        
        $stats = [
            'total' => $transactions->count(),
            'total_income' => $transactions->sum('total_biaya'),
            'lunas' => $transactions->where('status', 'lunas')->count(),
            'menunggu' => $transactions->where('status', 'menunggu')->count(),
            'batal' => $transactions->where('status', 'batal')->count(),
        ];
        
        // Payment method breakdown
        $paymentMethods = $transactions->groupBy('metode_pembayaran')->map->count();
        
        return [
            'type' => 'transactions',
            'data' => $transactions,
            'stats' => $stats,
            'payment_methods' => $paymentMethods,
            'title' => 'Laporan Transaksi'
        ];
    }

    private function getPatientReport($filter)
    {
        $query = Patient::withCount(['visits' => function($q) use ($filter) {
            $q->whereBetween('tanggal_kunjungan', [$filter['start_date'], $filter['end_date']]);
        }])->whereHas('visits', function($q) use ($filter) {
            $q->whereBetween('tanggal_kunjungan', [$filter['start_date'], $filter['end_date']]);
        });
        
        $patients = $query->get();
        
        $stats = [
            'total' => $patients->count(),
            'male' => $patients->where('jenis_kelamin', 'L')->count(),
            'female' => $patients->where('jenis_kelamin', 'P')->count(),
            'new_patients' => $patients->where('created_at', '>=', $filter['start_date'])->count(),
        ];
        
        // Age distribution
        $ageGroups = [
            '0-17' => $patients->filter(fn($p) => $p->umur <= 17)->count(),
            '18-30' => $patients->filter(fn($p) => $p->umur >= 18 && $p->umur <= 30)->count(),
            '31-45' => $patients->filter(fn($p) => $p->umur >= 31 && $p->umur <= 45)->count(),
            '46-60' => $patients->filter(fn($p) => $p->umur >= 46 && $p->umur <= 60)->count(),
            '60+' => $patients->filter(fn($p) => $p->umur > 60)->count(),
        ];
        
        return [
            'type' => 'patients',
            'data' => $patients,
            'stats' => $stats,
            'age_groups' => $ageGroups,
            'title' => 'Laporan Pasien'
        ];
    }

    private function getMedicineReport($filter)
    {
        $query = Medicine::with(['prescriptions' => function($q) use ($filter) {
            $q->whereBetween('created_at', [$filter['start_date'], $filter['end_date']])
              ->with('medicalRecord.visit');
        }]);
        
        $medicines = $query->get();
        
        $stats = [
            'total' => $medicines->count(),
            'low_stock' => $medicines->where('stok', '<=', 10)->count(),
            'out_of_stock' => $medicines->where('stok', 0)->count(),
            'total_stock_value' => $medicines->sum(fn($m) => $m->stok * $m->harga),
        ];
        
        // Most prescribed medicines
        $mostPrescribed = $medicines->sortByDesc(function($medicine) {
            return $medicine->prescriptions->sum('jumlah');
        })->take(10);
        
        return [
            'type' => 'medicines',
            'data' => $medicines,
            'stats' => $stats,
            'most_prescribed' => $mostPrescribed,
            'title' => 'Laporan Obat'
        ];
    }

    private function getIncomeReport($filter)
    {
        $transactions = Transaction::where('status', 'lunas')
            ->whereBetween('created_at', [$filter['start_date'], $filter['end_date']])
            ->get();
            
        $dailyIncome = $transactions->groupBy(function($t) {
            return $t->created_at->format('Y-m-d');
        })->map(function($group) {
            return $group->sum('total_biaya');
        });
        
        $monthlyIncome = $transactions->groupBy(function($t) {
            return $t->created_at->format('Y-m');
        })->map(function($group) {
            return $group->sum('total_biaya');
        });
        
        $stats = [
            'total_income' => $transactions->sum('total_biaya'),
            'average_daily' => $dailyIncome->avg(),
            'max_daily' => $dailyIncome->max(),
            'transaction_count' => $transactions->count(),
        ];
        
        return [
            'type' => 'income',
            'data' => $transactions,
            'stats' => $stats,
            'daily_income' => $dailyIncome,
            'monthly_income' => $monthlyIncome,
            'title' => 'Laporan Pendapatan'
        ];
    }

    public function export(Request $request)
    {
        $type = $request->type;
        $filter = $request->only(['start_date', 'end_date', 'doctor_id']);
        
        switch ($type) {
            case 'visits':
                $report = $this->getVisitReport($filter);
                $view = 'reports.exports.visits';
                break;
            case 'transactions':
                $report = $this->getTransactionReport($filter);
                $view = 'reports.exports.transactions';
                break;
            case 'patients':
                $report = $this->getPatientReport($filter);
                $view = 'reports.exports.patients';
                break;
            case 'income':
                $report = $this->getIncomeReport($filter);
                $view = 'reports.exports.income';
                break;
            default:
                abort(404);
        }
        
        $report['filter'] = $filter;
        
        $pdf = Pdf::loadView($view, $report);
        
        $filename = 'laporan-' . $type . '-' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function dashboardStats()
    {
        $today = now()->format('Y-m-d');
        $monthStart = now()->startOfMonth()->format('Y-m-d');
        $monthEnd = now()->endOfMonth()->format('Y-m-d');
        
        return [
            'today_visits' => Visit::whereDate('tanggal_kunjungan', $today)->count(),
            'today_income' => Transaction::whereDate('created_at', $today)
                ->where('status', 'lunas')
                ->sum('total_biaya'),
            'monthly_income' => Transaction::whereBetween('created_at', [$monthStart, $monthEnd])
                ->where('status', 'lunas')
                ->sum('total_biaya'),
            'low_stock_medicines' => Medicine::where('stok', '<=', 10)->count(),
            'total_patients' => Patient::count(),
            'pending_transactions' => Transaction::where('status', 'menunggu')->count(),
        ];
    }

  public function print(Request $request)
{
    $filter = $request->only(['start_date', 'end_date', 'type', 'doctor_id']);

    // Default date
    if (!$request->has('start_date')) {
        $filter['start_date'] = now()->startOfMonth()->format('Y-m-d');
        $filter['end_date']   = now()->endOfMonth()->format('Y-m-d');
    }

    switch ($request->get('type', 'visits')) {
        case 'visits':
            $report = $this->getVisitReport($filter);
            $view = 'reports.print-visits';
            break;
        case 'transactions':
            $report = $this->getTransactionReport($filter);
            $view = 'reports.print-transactions';
            break;
        case 'patients':
            $report = $this->getPatientReport($filter);
            $view = 'reports.print-patients';
            break;
        case 'medicines':
            $report = $this->getMedicineReport($filter);
            $view = 'reports.print-medicines';
            break;
        case 'income':
            $report = $this->getIncomeReport($filter);
            $view = 'reports.print-income';
            break;
        default:
            abort(404);
    }

    // Add clinic info and filter to report
    $report['clinic_info'] = [
    'name'    => 'Klinik Prima Medika',
    'address' => 'Jl. Raya Cileungsi No. 88, Cileungsi, Kabupaten Bogor, Jawa Barat',
    'phone'   => '(021) 8249-5566',
    'email'   => 'info@primamedika.com'
];

    
    $report['filter'] = $filter;
    $report['print_date'] = now()->format('d/m/Y H:i');

    $pdf = Pdf::loadView($view, $report)
        ->setPaper('A4', 'portrait')
        ->setOptions([
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
        ]);

    $filename = 'laporan-' . $report['type'] . '-' . date('Y-m-d') . '.pdf';
    
    return $pdf->stream($filename);
}

}