<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalRentals = Rental::count();
        $activeRentals = Rental::whereIn('status', ['active', 'extended'])->count();
        $overdueRentals = Rental::where('status', 'overdue')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();

        // Recent rentals
        $recentRentals = Rental::with(['user', 'film'])
                              ->orderBy('created_at', 'desc')
                              ->take(10)
                              ->get();

        // Pending payments
        $pendingPaymentsList = Payment::with(['rental.film', 'user'])
                                     ->where('payment_method', 'manual')
                                     ->where('status', 'pending')
                                     ->orderBy('created_at', 'desc')
                                     ->take(10)
                                     ->get();

        // Monthly revenue
        $monthlyRevenue = Payment::where('status', 'verified')
                                ->whereMonth('verified_at', Carbon::now()->month)
                                ->sum('amount');

        return view('pegawai.dashboard', compact(
            'totalRentals',
            'activeRentals',
            'overdueRentals',
            'pendingPayments',
            'recentRentals',
            'pendingPaymentsList',
            'monthlyRevenue'
        ));
    }
}
