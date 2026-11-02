<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STOK PRODUK</title>
    <style>
        body{
            font-family: sans-serif;
            font-size: 12px;
        }
        table{
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td{
            border: 1px solid #000;
        }
        th, td{
            padding: 8px;
            text-align: left;
        }
        h2{
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Laporan Stok Produk</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produks as $produk)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $produk->nama_produk }}</td>
                <td>{{ $produk->subkategori->nama_subkategori ?? '-' }}</td>
                <td>Rp {{ number_format($produk->harga,0,',','.') }}</td>
                <td>{{ $produk->stok }}</td>
                <td>
                    @if ($produk->stok == 0)
                        Habis
                    @elseif ($produk->stok <= 22)
                        Hampir Habis
                    @else
                        Aman
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>