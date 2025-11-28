<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice {{ $order->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            color: #2d5f3f;
            margin-bottom: 5px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        table th {
            background-color: #2d5f3f;
            color: white;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .total-section table {
            width: 300px;
            margin-left: auto;
        }
        .total-section table td {
            border: none;
            padding: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>🏔️ RESN OUTDOOR GEAR STORE</h1>
        <p>Jl. Pembangkit, Cianjur</p>
        <p>Telp: +62 822 5872 4734| Email: resn@oudoorgear.com</p>
    </div>

    <div class="invoice-details">
        <table style="border: none;">
            <tr>
                <td style="border: none;"><strong>No. Invoice:</strong></td>
                <td style="border: none;">{{ $order->invoice_number }}</td>
                <td style="border: none;"><strong>Tanggal:</strong></td>
                <td style="border: none;">{{ $order->order_date->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td style="border: none;"><strong>Customer:</strong></td>
                <td style="border: none;">{{ $order->customer ? $order->customer->name : 'Umum' }}</td>
                <td style="border: none;"><strong>Kasir:</strong></td>
                <td style="border: none;">@auth <span>{{ Auth::user()->name }}</span>
            @else
    <span>Guest</span>
@endauth
</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <table>
            <tr>
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Bayar:</td>
                <td class="text-right">Rp {{ number_format($order->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian:</td>
                <td class="text-right">Rp {{ number_format($order->change_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p>Selamat berpetualang! 🏕️</p>
    </div>

    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #2d5f3f; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Cetak Invoice
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Tutup
        </button>
    </div>
</body>
</html>
