<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
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
            background: #3498db;
            color: white;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-return_pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .btn-print {
            padding: 10px 20px;
            background: #3498db;
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
        <h1>LAPORAN PEMINJAMAN BUKU</h1>
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
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                <td>
                    @if($loan->status === 'pending')
                        <span class="status status-pending">Menunggu Persetujuan</span>
                    @elseif($loan->status === 'approved')
                        <span class="status status-approved">Sedang Dipinjam</span>
                    @elseif($loan->status === 'return_pending')
                        <span class="status status-return_pending">Menunggu Konfirmasi Pengembalian</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Tidak ada data peminjaman aktif</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 30px;">
        <p><strong>Total Peminjaman Aktif:</strong> {{ $loans->count() }} transaksi</p>
        <p><strong>Menunggu Persetujuan:</strong> {{ $loans->where('status', 'pending')->count() }} transaksi</p>
        <p><strong>Sedang Dipinjam:</strong> {{ $loans->where('status', 'approved')->count() }} transaksi</p>
        <p><strong>Menunggu Konfirmasi Pengembalian:</strong> {{ $loans->where('status', 'return_pending')->count() }} transaksi</p>
    </div>
</body>
</html>
