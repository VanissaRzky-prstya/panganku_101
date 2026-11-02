<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PENDAPATAN PRODUK</title>
    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        h2{
            text-align: center;
            margin-bottom: 5px;
        }
        p{
            text-align: center;
            margin-top: 0;
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
            background: #f2f2f2;
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
    <h2>Laporan Pendapatan Produk</h2>
    <p>Dicetak pada :{{ now()->format('d-m-Y H:i') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Jumlah Terjual</th>
                <th>Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produks as $index => $produk)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $produk->produk->nama_produk ?? '-' }}</td>
                    <td>{{ $produk->produk->subkategori->nama_subkategori ?? '-' }}</td>
                    <td class="text-center">{{ $produk->total_terjual }}</td>
                    <td class="text-right">Rp {{ number_format($produk->total_pendapatan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>