<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Film;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // Dashboard laporan
    public function index()
    {
        $totalRentals = Rental::count();
        $activeRentals = Rental::whereIn('status', ['active', 'extended'])->count();
        $overdueRentals = Rental::where('status', 'overdue')->count();
        $completedRentals = Rental::where('status', 'returned')->count();

        $totalRevenue = Payment::where('status', 'verified')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'verified')
                                ->whereMonth('verified_at', Carbon::now()->month)
                                ->sum('amount');

        $pendingPayments = Payment::where('status', 'pending')->count();

        $popularFilms = Film::withCount(['rentals' => function($q) {
                            $q->where('status', '!=', 'pending');
                        }])
                        ->orderBy('rentals_count', 'desc')
                        ->take(10)
                        ->get();

        return view('pegawai.reports.index', compact(
            'totalRentals',
            'activeRentals',
            'overdueRentals',
            'completedRentals',
            'totalRevenue',
            'monthlyRevenue',
            'pendingPayments',
            'popularFilms'
        ));
    }

    // Laporan transaksi
    public function transactions(Request $request)
    {
        $query = Rental::with(['user', 'film', 'payments']);

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('rental_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('rental_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $rentals = $query->orderBy('rental_date', 'desc')->paginate(20);
        $totalRevenue = Payment::whereIn('rental_id', $rentals->pluck('id'))
                              ->where('status', 'verified')
                              ->sum('amount');

        return view('pegawai.reports.transactions', compact('rentals', 'totalRevenue'));
    }

    // Export laporan ke PDF
    public function exportPdf(Request $request)
    {
        $query = Rental::with(['user', 'film', 'payments']);

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('rental_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('rental_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $rentals = $query->orderBy('rental_date', 'desc')->get();
        $totalRevenue = Payment::whereIn('rental_id', $rentals->pluck('id'))
                              ->where('status', 'verified')
                              ->sum('amount');

        $pdf = Pdf::loadView('pegawai.reports.pdf', compact('rentals', 'totalRevenue'));
        
        return $pdf->download('laporan-transaksi-' . date('Y-m-d') . '.pdf');
    }

    // Export laporan ke CSV
    public function exportCsv(Request $request)
    {
        $query = Rental::with(['user', 'film', 'payments']);

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('rental_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('rental_date', '<=', $request->end_date);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $rentals = $query->orderBy('rental_date', 'desc')->get();

        $filename = 'laporan-transaksi-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($rentals) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Kode Rental',
                'Tanggal Sewa',
                'Nama User',
                'Film',
                'Durasi (hari)',
                'Total Pembayaran',
                'Status',
                'Tanggal Jatuh Tempo',
                'Tanggal Kembali',
            ]);

            // Data
            foreach ($rentals as $rental) {
                fputcsv($file, [
                    $rental->rental_code,
                    $rental->rental_date->format('Y-m-d'),
                    $rental->user->name,
                    $rental->film->title,
                    $rental->rental_days,
                    $rental->total,
                    $rental->status,
                    $rental->due_date->format('Y-m-d'),
                    $rental->return_date ? $rental->return_date->format('Y-m-d') : '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
