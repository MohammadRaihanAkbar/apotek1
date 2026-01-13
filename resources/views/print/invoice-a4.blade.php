<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 30px;
        }

        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-top {
            display: table;
            width: 100%;
        }

        .company-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .invoice-title {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: top;
        }

        .invoice-title h1 {
            margin: 0;
            color: #3b82f6;
            font-size: 28px;
            text-transform: uppercase;
        }

        .details {
            margin-bottom: 30px;
            display: table;
            width: 100%;
        }

        .details-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .table th {
            background-color: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        .table td {
            border: 1px solid #e5e7eb;
            padding: 12px;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            float: right;
            width: 250px;
        }

        .summary-row {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-row.total {
            border-bottom: 2px solid #3b82f6;
            font-weight: bold;
            font-size: 14px;
            color: #3b82f6;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            background-color: #dcfce7;
            color: #166534;
            font-weight: bold;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header-top">
                <div class="company-info">
                    <h2 style="margin:0; color:#1e40af;">APOTEK KITA</h2>
                    <p style="margin:5px 0;">Jl. Sehat No. 123, Kota Sehat<br>
                        Telp: 0812-3456-7890<br>
                        Email: admin@apotekkita.id</p>
                </div>
                <div class="invoice-title">
                    <h1>INVOICE</h1>
                    <div class="badge">PAID / LUNAS</div>
                </div>
            </div>
        </div>

        <div class="details">
            <div class="details-col">
                <h4 style="margin-bottom:5px;">Informasi Transaksi:</h4>
                <table style="width: 100%;">
                    <tr>
                        <td style="padding:2px 0; width:100px;">No. Invoice</td>
                        <td style="padding:2px 0;">: <strong>{{ $sale->invoice_number }}</strong></td>
                    </tr>
                    <tr>
                        <td style="padding:2px 0;">Tanggal</td>
                        <td style="padding:2px 0;">: {{ $sale->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:2px 0;">Kasir</td>
                        <td style="padding:2px 0;">: {{ $sale->user->name }}</td>
                    </tr>
                </table>
            </div>
            <div class="details-col text-right">
                <h4 style="margin-bottom:5px;">Ditujukan Kepada:</h4>
                <p style="margin:0;">Pelanggan Umum<br>Apotek Kita</p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Nama Produk</th>
                    <th style="width: 100px;">Harga Satuan</th>
                    <th style="width: 60px;">Qty</th>
                    <th style="width: 120px;" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <small>{{ $item->product_code }}</small>
                        </td>
                        <td>Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td>{{ $item->qty }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-row">
                <table style="width: 100%;">
                    <tr>
                        <td>Subtotal</td>
                        <td class="text-right">Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
            @if($sale->discount > 0)
                <div class="summary-row">
                    <table style="width: 100%;">
                        <tr style="color: #dc2626;">
                            <td>Diskon</td>
                            <td class="text-right">-Rp {{ number_format($sale->discount, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            @endif
            <div class="summary-row total">
                <table style="width: 100%;">
                    <tr>
                        <td><strong>TOTAL AKHIR</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="margin-top: 10px;">
                <table style="width: 100%; font-size: 11px;">
                    <tr>
                        <td>Bayar Tunai</td>
                        <td class="text-right">Rp {{ number_format($sale->paid, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Kembalian</td>
                        <td class="text-right">Rp {{ number_format($sale->change, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="clear: both;"></div>

        <div class="footer">
            <p>Terima kasih telah berbelanja di Apotek Kita. Semoga Anda cepat sembuh!<br>
                Invoice ini sah dan diproses secara otomatis oleh sistem POS Apotek Kita.</p>
        </div>
    </div>
</body>

</html>