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
        $stats = [
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'active_rentals' => Rental::whereIn('status', ['active', 'extended'])->count(),
            'overdue_rentals' => Rental::where('status', 'overdue')->count(),
            'today_revenue' => Payment::where('status', 'paid')
                                    ->whereDate('paid_at', Carbon::today())
                                    ->sum('amount'),
        ];

        // Recent rentals
        $recentRentals = Rental::with(['user', 'film'])
                              ->orderBy('created_at', 'desc')
                              ->take(5)
                              ->get();

        // Recent payments (pending)
        $recentPayments = Payment::with(['rental.user'])
                                 ->where('status', 'pending')
                                 ->orderBy('created_at', 'desc')
                                 ->take(5)
                                 ->get();

        return view('pegawai.dashboard', compact('stats', 'recentRentals', 'recentPayments'));
    }
}
