<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Struk {{ $purchase->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
            margin: 8px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .line {
            border-top: 1px dashed #333;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 2px 0;
            vertical-align: top;
        }

        .right {
            text-align: right;
        }

        .small {
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div class="center">
        <div class="bold">Alfa Beta</div>
        <div class="small">Bukti Pembayaran</div>
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td>No. Invoice</td>
            <td class="right">{{ $purchase->invoice_number }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td class="right">{{ $purchase->purchase_date->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td class="right">{{ $purchase->user->name }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td class="right">{{ $purchase->is_member ? 'Member' : 'Non-Member' }}</td>
        </tr>
    </table>

    @if ($purchase->is_member)
        <div class="line"></div>
        <div class="small">{{ $purchase->customer_name ?? '-' }}</div>
        <div class="small">{{ $purchase->customer_phone ?? '-' }}</div>
    @endif

    <div class="line"></div>

    @foreach ($purchase->items as $item)
        <div>{{ $item->product_name }}</div>
        <table>
            <tr>
                <td>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
        </table>
    @endforeach

    <div class="line"></div>

    <table>
        <tr>
            <td>Total Awal</td>
            <td class="right">Rp {{ number_format($purchase->total_before_discount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon Poin</td>
            <td class="right">Rp {{ number_format($purchase->points_discount_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="bold">Total Bayar</td>
            <td class="right bold">Rp {{ number_format($purchase->total_after_discount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Uang Dibayar</td>
            <td class="right">Rp {{ number_format($purchase->cash_paid, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td class="right">Rp {{ number_format($purchase->change_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    @if ($purchase->is_member)
        <div class="line"></div>
        <div class="small">Poin Digunakan: {{ $purchase->points_used }}</div>
        <div class="small">Poin Didapat: {{ $purchase->points_earned }}</div>
    @endif

    <div class="line"></div>
    <div class="center small">Terima kasih atas kunjungan Anda.</div>
    <div class="center small">Simpan struk ini sebagai bukti transaksi.</div>
</body>

</html>
