<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengembalian</title>
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
            font-size: 12px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background: #2ecc71;
            color: white;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .btn-print {
            padding: 10px 20px;
            background: #2ecc71;
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
        <h1>LAPORAN PENGEMBALIAN BUKU</h1>
        <p>Perpustakaan Digital</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Jatuh Tempo</th>
                <th>Tgl Dikembalikan</th>
                <th>Keterlambatan</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returns as $index => $return)
            @php
                $loanDate = \Carbon\Carbon::parse($return->loan_date);
                $dueDate = \Carbon\Carbon::parse($return->due_date);
                $returnDate = \Carbon\Carbon::parse($return->return_date);
                $daysLate = $returnDate->gt($dueDate) ? $returnDate->diffInDays($dueDate) : 0;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $return->user->name }}</td>
                <td>{{ $return->book->title }}</td>
                <td>{{ $loanDate->format('d/m/Y') }}</td>
                <td>{{ $dueDate->format('d/m/Y') }}</td>
                <td>{{ $returnDate->format('d/m/Y') }}</td>
                <td>{{ $daysLate }} hari</td>
                <td>Rp {{ number_format($return->fine ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data pengembalian</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 30px;">
        <p><strong>Total Pengembalian:</strong> {{ $returns->count() }} transaksi</p>
        <p><strong>Total Denda:</strong> Rp {{ number_format($returns->sum('fine'), 0, ',', '.') }}</p>
    </div>
</body>
</html>
