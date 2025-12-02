<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('owner.reports.index');
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        // Get sales data
        $payments = Payment::with(['rental.user', 'rental.film'])
                          ->where('status', 'verified')
                          ->orderBy('verified_at', 'desc')
                          ->get();

        if ($format === 'csv') {
            return $this->exportCSV($payments);
        } elseif ($format === 'pdf') {
            return $this->exportPDF($payments);
        }

        return back()->with('error', 'Invalid export format');
    }

    private function exportCSV($payments)
    {
        $filename = 'sales_report_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'Payment Code',
                'Customer Name',
                'Film Title',
                'Rental Code',
                'Payment Method',
                'Amount',
                'Payment Date',
                'Rental Days',
                'Rental Date',
                'Due Date'
            ]);

            // Data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->payment_code,
                    $payment->rental->user->name,
                    $payment->rental->film->title,
                    $payment->rental->rental_code,
                    ucfirst($payment->payment_method),
                    $payment->amount,
                    $payment->verified_at ? $payment->verified_at->format('Y-m-d H:i:s') : '-',
                    $payment->rental->rental_days,
                    $payment->rental->rental_date->format('Y-m-d'),
                    $payment->rental->due_date->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportPDF($payments)
    {
        // Calculate totals
        $totalRevenue = $payments->sum('amount');
        $totalTransactions = $payments->count();
        
        // Group by payment method
        $byMethod = $payments->groupBy('payment_method')->map(function($group) {
            return [
                'count' => $group->count(),
                'total' => $group->sum('amount')
            ];
        });

        $html = view('owner.reports.pdf', compact('payments', 'totalRevenue', 'totalTransactions', 'byMethod'))->render();

        // Simple HTML to PDF conversion using DomPDF would require package installation
        // For now, we'll return HTML that can be printed as PDF
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="sales_report_' . date('Y-m-d_His') . '.html"');
    }
}
