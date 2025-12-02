<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sales Report - {{ date('Y-m-d') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item .label {
            color: #666;
            font-size: 11px;
            margin-bottom: 5px;
        }
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #4F46E5;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 11px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .method-summary {
            margin-bottom: 30px;
        }
        .method-summary h3 {
            margin-bottom: 15px;
            color: #333;
        }
        .method-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #f9f9f9;
            margin-bottom: 5px;
            border-left: 3px solid #4F46E5;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RENTAL FILM - SALES REPORT</h1>
        <p>Generated on {{ date('d F Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Transactions</div>
            <div class="value">{{ $totalTransactions }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Revenue</div>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Average Transaction</div>
            <div class="value">Rp {{ number_format($totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="method-summary">
        <h3>Payment Methods Summary</h3>
        @foreach($byMethod as $method => $data)
            <div class="method-item">
                <span><strong>{{ ucfirst($method) }}</strong> - {{ $data['count'] }} transactions</span>
                <span><strong>Rp {{ number_format($data['total'], 0, ',', '.') }}</strong></span>
            </div>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%">Payment Code</th>
                <th style="width: 15%">Customer</th>
                <th style="width: 18%">Film</th>
                <th style="width: 10%">Method</th>
                <th style="width: 12%" class="text-right">Amount</th>
                <th style="width: 8%">Days</th>
                <th style="width: 12%">Rental Date</th>
                <th style="width: 13%">Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr>
                    <td>{{ $payment->payment_code }}</td>
                    <td>{{ $payment->rental->user->name }}</td>
                    <td>{{ Str::limit($payment->rental->film->title, 30) }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></td>
                    <td>{{ $payment->rental->rental_days }} days</td>
                    <td>{{ $payment->rental->rental_date->format('d M Y') }}</td>
                    <td>{{ $payment->verified_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer-generated report. © {{ date('Y') }} Rental Film System</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="background: #4F46E5; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            Print / Save as PDF
        </button>
    </div>
</body>
</html>
