<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaction Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            background-color: #f4f4f4;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .summary table {
            width: 100%;
        }
        .summary td {
            padding: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        table tr:hover {
            background-color: #f9f9f9;
        }
        .total-row {
            background-color: #f4f4f4;
            font-weight: bold;
            font-size: 12px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        .status-active { background-color: #d1fae5; color: #065f46; }
        .status-returned { background-color: #dbeafe; color: #1e40af; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RENTAL FILM</h1>
        <h2>Transaction Report</h2>
        <p>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</p>
        @if($status)
            <p>Status: <strong>{{ ucfirst($status) }}</strong></p>
        @endif
        <p>Generated: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td><strong>Total Transactions:</strong></td>
                <td class="text-right">{{ $rentals->count() }}</td>
                <td><strong>Total Revenue:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    @if($rentals->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="8%">Date</th>
                    <th width="18%">Customer</th>
                    <th width="22%">Film</th>
                    <th width="6%">Days</th>
                    <th width="12%">Amount</th>
                    <th width="12%">Late Fee</th>
                    <th width="12%">Total</th>
                    <th width="10%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rentals as $rental)
                    <tr>
                        <td>{{ $rental->rental_date->format('d/m/Y') }}</td>
                        <td>
                            <strong>{{ $rental->user->name }}</strong><br>
                            <small style="color: #666;">{{ $rental->user->email }}</small>
                        </td>
                        <td>
                            <strong>{{ $rental->film->title }}</strong><br>
                            <small style="color: #666;">{{ $rental->film->genre->name }}</small>
                        </td>
                        <td class="text-center">{{ $rental->rental_days }}</td>
                        <td class="text-right">Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</td>
                        <td class="text-right">
                            @if($rental->late_fee > 0)
                                <span style="color: #991b1b;">Rp {{ number_format($rental->late_fee, 0, ',', '.') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right"><strong>Rp {{ number_format($rental->total_amount + $rental->late_fee, 0, ',', '.') }}</strong></td>
                        <td class="text-center">
                            <span class="status-badge status-{{ $rental->status }}">{{ $rental->status }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="6" class="text-right">TOTAL REVENUE:</td>
                    <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @else
        <p style="text-align: center; padding: 40px; color: #999;">No transactions found for this period</p>
    @endif

    <div class="footer">
        <p>This is a computer-generated report from Rental Film Management System</p>
        <p>Printed on {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
