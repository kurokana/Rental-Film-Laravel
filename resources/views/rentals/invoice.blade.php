<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $rental->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
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
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        .total-section table {
            margin-left: auto;
            width: 300px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-paid { background-color: #d1fae5; color: #065f46; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="header">
        <h1>RENTAL FILM</h1>
        <p>Invoice #{{ $rental->id }}</p>
    </div>

    <!-- Customer Info -->
    <div class="info-section">
        <h3>Customer Information</h3>
        <table>
            <tr>
                <td width="150"><strong>Name:</strong></td>
                <td>{{ $rental->user->name }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $rental->user->email }}</td>
            </tr>
            <tr>
                <td><strong>Phone:</strong></td>
                <td>{{ $rental->user->phone ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Rental Info -->
    <div class="info-section">
        <h3>Rental Information</h3>
        <table>
            <tr>
                <td width="150"><strong>Rental Date:</strong></td>
                <td>{{ $rental->rental_date->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Due Date:</strong></td>
                <td>{{ $rental->due_date->format('d F Y') }}</td>
            </tr>
            @if($rental->return_date)
            <tr>
                <td><strong>Return Date:</strong></td>
                <td>{{ $rental->return_date->format('d F Y') }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span class="status status-{{ $rental->status }}">{{ strtoupper($rental->status) }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Film Details -->
    <div class="info-section">
        <h3>Film Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Film</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Days</th>
                    <th>Price/Day</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $rental->film->title }}</td>
                    <td>{{ $rental->film->genre->name }}</td>
                    <td>{{ $rental->film->year }}</td>
                    <td>{{ $rental->rental_days }}</td>
                    <td>Rp {{ number_format($rental->film->rental_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($rental->film->rental_price * $rental->rental_days, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Payment Info -->
    @if($rental->payment)
    <div class="info-section">
        <h3>Payment Information</h3>
        <table>
            <tr>
                <td width="150"><strong>Payment Code:</strong></td>
                <td>{{ $rental->payment->payment_code }}</td>
            </tr>
            <tr>
                <td><strong>Method:</strong></td>
                <td>{{ ucfirst($rental->payment->payment_method) }}</td>
            </tr>
            <tr>
                <td><strong>Payment Date:</strong></td>
                <td>{{ $rental->payment->payment_date ? $rental->payment->payment_date->format('d F Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span class="status status-{{ $rental->payment->status }}">{{ strtoupper($rental->payment->status) }}</span>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <!-- Total -->
    <div class="total-section">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>Rp {{ number_format($rental->total_amount, 0, ',', '.') }}</td>
            </tr>
            @if($rental->late_fee > 0)
            <tr>
                <td><strong>Late Fee:</strong></td>
                <td>Rp {{ number_format($rental->late_fee, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr style="font-size: 14px;">
                <td><strong>Total Amount:</strong></td>
                <td><strong>Rp {{ number_format($rental->total_amount + $rental->late_fee, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for renting with us!</p>
        <p>This is a computer generated invoice and does not require a signature.</p>
        <p>For inquiries, please contact: support@rentalfilm.com</p>
    </div>
</body>
</html>
