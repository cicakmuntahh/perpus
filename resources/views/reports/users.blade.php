<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data User</title>
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
            background: #f39c12;
            color: white;
        }
        
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        
        .btn-print {
            padding: 10px 20px;
            background: #f39c12;
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
        <h1>LAPORAN DATA ANGGOTA PERPUSTAKAAN</h1>
        <p>Perpustakaan Digital</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Tidak ada data anggota</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div style="margin-top: 30px;">
        <p><strong>Total Anggota:</strong> {{ $users->count() }} orang</p>
    </div>
</body>
</html>
