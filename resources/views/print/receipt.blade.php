<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $sale->invoice_number }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;
            font-family: monospace;
            font-size: 9pt;
        }

        .print-container {
            width: 80mm;
            padding: 4mm;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .border-dashed {
            border-bottom: 1px dashed #000;
            margin: 2mm 0;
        }

        .my-2 {
            margin-top: 2mm;
            margin-bottom: 2mm;
        }

        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body onload="window.print(); window.onafterprint = function(){ window.close(); }">
    <div class="print-container">
        <div class="text-center">
            <div class="font-bold" style="font-size: 14pt; text-transform: uppercase;">APOTEK KITA</div>
            <div>Jl. Sehat No. 123, Kota Sehat</div>
            <div>Telp: 0812-3456-7890</div>
        </div>

        <div class="border-dashed"></div>

        <table>
            <tr>
                <td>No: {{ $sale->invoice_number }}</td>
                <td class="text-right">{{ $sale->created_at->format('d/m/y H:i') }}</td>
            </tr>
            <tr>
                <td colspan="2">Kasir: {{ $sale->user->name }}</td>
            </tr>
        </table>

        <div class="border-dashed"></div>

        <table>
            @foreach($sale->items as $item)
                <tr>
                    <td colspan="2" style="padding-top: 1mm;">{{ $item->product_name }}</td>
                </tr>
                <tr>
                    <td>{{ $item->qty }} x {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right font-bold">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="border-dashed"></div>

        <table>
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ number_format($sale->total, 0, ',', '.') }}</td>
            </tr>
            @if($sale->discount > 0)
                <tr>
                    <td>Diskon</td>
                    <td class="text-right">-{{ number_format($sale->discount, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="font-bold" style="font-size: 11pt;">
                <td style="padding: 1mm 0;">TOTAL</td>
                <td class="text-right">Rp {{ number_format($sale->grand_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Bayar</td>
                <td class="text-right">{{ number_format($sale->paid, 0, ',', '.') }}</td>
            </tr>
            <tr class="font-bold">
                <td>Kembali</td>
                <td class="text-right">{{ number_format($sale->change, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="border-dashed"></div>

        <div class="text-center italic my-2">
            <div class="font-bold">TERIMA KASIH</div>
            <div>Semoga Cepat Sembuh</div>
            <div style="font-size: 7pt; margin-top: 1mm;">Barang yang sudah dibeli tidak dapat ditukar/dikembalikan
            </div>
        </div>
    </div>
</body>

</html>