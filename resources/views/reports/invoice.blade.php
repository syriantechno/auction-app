<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; margin: 40px; font-size: 13px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { width: 100%; border-bottom: 2px solid #2563eb; padding-bottom: 20px; display: table; }
        .header div { display: table-cell; vertical-align: top; }
        .logo { font-size: 28px; font-weight: bold; color: #1e40af; }
        .invoice-info { text-align: right; }
        .details { width: 100%; margin-top: 40px; display: table; }
        .details div { display: table-cell; width: 50%; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 50px; }
        .items-table th { background: #f3f4f6; border: 1px solid #e5e7eb; padding: 12px; text-align: left; }
        .items-table td { border: 1px solid #e5e7eb; padding: 12px; }
        .totals { margin-top: 30px; text-align: right; }
        .totals div { font-size: 16px; font-weight: bold; margin-bottom: 5px; }
        .footer { margin-top: 100px; text-align: center; color: #999; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <span class="logo">AUTOMAZAD</span><br>
            Premium Car Auctions LLC<br>
            Dubai, United Arab Emirates
        </div>
        <div class="invoice-info">
            <h2 style="margin:0; color: #2563eb;">INVOICE</h2>
            No: INV-{{ $invoice->id }}-{{ date('Y') }}<br>
            Date: {{ $invoice->created_at->format('M d, Y') }}<br>
            Status: {{ strtoupper($invoice->status) }}
        </div>
    </div>

    <div class="details">
        <div>
            <strong>BILLED TO:</strong><br>
            {{ $invoice->user->name }}<br>
            {{ $invoice->user->email }}
        </div>
        <div style="text-align: right;">
            <strong>VEHICLE DETAILS:</strong><br>
            {{ $invoice->auction->car->year }} {{ $invoice->auction->car->make }} {{ $invoice->auction->car->model }}<br>
            VIN: {{ $invoice->auction->car->vin }}
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Winning Auction Bid (Lot #{{ $invoice->auction_id }})</td>
                <td style="text-align: right;">${{ number_format($invoice->amount, 2) }}</td>
            </tr>
            <tr>
                <td>Documentation & Admin Fee</td>
                <td style="text-align: right;">$0.00</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <div>Total Amount: ${{ number_format($invoice->amount, 2) }}</div>
        <p style="font-size: 11px; font-weight: normal; color: #666;">Payment is due within 48 hours of auction end.</p>
    </div>

    <div class="footer">
        Automazad Auctions - Professional Excellence in Quality.
    </div>
</body>
</html>

