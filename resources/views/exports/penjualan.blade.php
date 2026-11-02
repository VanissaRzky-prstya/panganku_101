<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN PENJUALAN</title>
    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2{
            text-align: center;
            margin-bottom: 20px;
        }
        table{
            width: 100%;
            border-collapse: collapse;
        }
        table,th,td{
            border: 1px solid #000;
        }
        th{
            background: #e5e5e5;
            text-align: center;
            padding: 8px;
        }
        td{
            padding: 6px;
        }
        .text-center{
            text-align: center;
        }
        .text-right{
            text-align: right;
        }
    </style>
</head>
<body>
    <h2>LAPORAN PENJUALAN</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pesanan</th>
                <th>Pelanggan</th>
                <th>Total Bayar</th>
                <th>Status Pembayaran</th>
                <th>Status Pesanan</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $order->kode_pesanan }}</td>
                    <td>{{ $order->user->name ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($order->total_bayar,0,',','.') }}</td>
                    <td>{{ ucfirst($order->status_pembayaran) }}</td>
                    <td>{{ ucfirst($order->status_pesanan) }}</td>
                    <td class="text-center">{{ $order->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>