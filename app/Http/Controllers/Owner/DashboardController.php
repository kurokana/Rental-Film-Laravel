<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Film;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalPegawai = User::where('role', 'pegawai')->count();
        $totalFilms = Film::count();
        $totalRentals = Rental::count();

        $activeRentals = Rental::whereIn('status', ['active', 'extended'])->count();
        $overdueRentals = Rental::where('status', 'overdue')->count();
        $completedRentals = Rental::where('status', 'returned')->count();

        // Revenue
        $totalRevenue = Payment::where('status', 'verified')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'verified')
                                ->whereMonth('verified_at', Carbon::now()->month)
                                ->sum('amount');
        $todayRevenue = Payment::where('status', 'verified')
                              ->whereDate('verified_at', Carbon::today())
                              ->sum('amount');

        // Charts data - Monthly revenue for last 6 months
        $monthlyRevenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = Payment::where('status', 'verified')
                             ->whereYear('verified_at', $month->year)
                             ->whereMonth('verified_at', $month->month)
                             ->sum('amount');
            
            $monthlyRevenueData[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue
            ];
        }

        // Popular films
        $popularFilms = Film::withCount(['rentals' => function($q) {
                            $q->where('status', '!=', 'pending');
                        }])
                        ->orderBy('rentals_count', 'desc')
                        ->take(10)
                        ->get();

        // Recent activities
        $recentRentals = Rental::with(['user', 'film'])
                              ->orderBy('created_at', 'desc')
                              ->take(10)
                              ->get();

        return view('owner.dashboard', compact(
            'totalUsers',
            'totalPegawai',
            'totalFilms',
            'totalRentals',
            'activeRentals',
            'overdueRentals',
            'completedRentals',
            'totalRevenue',
            'monthlyRevenue',
            'todayRevenue',
            'monthlyRevenueData',
            'popularFilms',
            'recentRentals'
        ));
    }
}
