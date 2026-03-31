<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Buku</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        th {
            background: #667eea;
            color: white;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .btn-print {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        
        .btn-back {
            padding: 10px 20px;
            background: #95a5a6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            margin-left: 10px;
            text-decoration: none;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Laporan</button>
        <a href="{{ route($role . '.reports') }}" class="btn-back">← Kembali</a>
    </div>
    
    <div class="header">
        <h1>LAPORAN DATA BUKU</h1>
        <p>Perpustakaan Digital</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Kategori</th>
                <th>ISBN</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Tersedia</th>
            </tr>
        </thead>
        <tbody>
            @forelse($books as $index => $book)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->category->name }}</td>
                <td>{{ $book->isbn }}</td>
                <td>{{ $book->publisher }}</td>
                <td>{{ $book->year }}</td>
                <td>{{ $book->stock }}</td>
                <td>{{ $book->available }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data buku</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 30px;">
        <p><strong>Total Buku:</strong> {{ $books->count() }} judul</p>
        <p><strong>Total Stok:</strong> {{ $books->sum('stock') }} eksemplar</p>
        <p><strong>Total Tersedia:</strong> {{ $books->sum('available') }} eksemplar</p>
    </div>
</body>
</html>
