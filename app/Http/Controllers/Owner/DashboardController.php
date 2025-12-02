<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Film;
use App\Models\User;
use App\Models\AuditLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_customers' => User::where('role', 'user')->count(),
            'new_customers_month' => User::where('role', 'user')
                                        ->whereMonth('created_at', Carbon::now()->month)
                                        ->count(),
            'total_pegawai' => User::where('role', 'pegawai')->count(),
            'total_films' => Film::count(),
            'available_films' => Film::where('stock', '>', 0)->count(),
            'total_rentals' => Rental::count(),
            'active_rentals' => Rental::whereIn('status', ['active', 'extended'])->count(),
            'overdue_rentals' => Rental::where('status', 'overdue')->count(),
            'completed_rentals' => Rental::where('status', 'returned')->count(),
            'total_revenue' => Payment::where('status', 'verified')->sum('amount'),
            'monthly_revenue' => Payment::where('status', 'verified')
                                    ->whereMonth('verified_at', Carbon::now()->month)
                                    ->sum('amount'),
            'today_revenue' => Payment::where('status', 'verified')
                                  ->whereDate('verified_at', Carbon::today())
                                  ->sum('amount'),
            'active_promos' => \App\Models\Promo::where('is_active', true)
                                  ->where('end_date', '>=', Carbon::now())
                                  ->count(),
        ];

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

        // Recent audit logs
        $recentLogs = AuditLog::with('user')
                             ->orderBy('created_at', 'desc')
                             ->take(10)
                             ->get();

        return view('owner.dashboard', compact(
            'stats',
            'monthlyRevenueData',
            'popularFilms',
            'recentRentals',
            'recentLogs'
        ));
    }
}
