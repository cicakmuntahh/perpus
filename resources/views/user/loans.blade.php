@extends('layouts.user')

@section('title', 'Pinjaman Saya')
@section('page-title', 'Pinjaman Saya')
@section('page-subtitle', 'Kelola buku yang sedang Anda pinjam')

@section('content')
<style>
    .loans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .loan-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .loan-card:hover {
        transform: translateY(-5px);
    }

    .loan-header {
        display: flex;
        gap: 1rem;
        padding: 1.5rem;
        border-bottom: 1px solid #ecf0f1;
    }

    .book-cover {
        width: 80px;
        height: 110px;
        border-radius: 8px;
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-info {
        flex: 1;
    }

    .book-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.3rem;
    }

    .book-author {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .loan-status {
        padding: 0.3rem 0.8rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .loan-status.active {
        background: #cce5ff;
        color: #004085;
    }

    .loan-status.due-soon {
        background: #fff3cd;
        color: #856404;
    }

    .loan-status.overdue {
        background: #f8d7da;
        color: #721c24;
    }

    .loan-body {
        padding: 1.5rem;
    }

    .loan-detail {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }

    .loan-detail-label {
        color: #7f8c8d;
    }

    .loan-detail-value {
        color: #2c3e50;
        font-weight: 600;
    }

    .due-date {
        color: #e74c3c;
    }

    .progress-bar {
        height: 8px;
        background: #ecf0f1;
        border-radius: 10px;
        overflow: hidden;
        margin: 1rem 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #2ecc71 0%, #27ae60 100%);
        border-radius: 10px;
    }

    .progress-fill.warning {
        background: linear-gradient(90deg, #f39c12 0%, #e67e22 100%);
    }

    .progress-fill.danger {
        background: linear-gradient(90deg, #e74c3c 0%, #c0392b 100%);
    }

    .loan-footer {
        display: flex;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid #ecf0f1;
    }

    .btn {
        flex: 1;
        padding: 0.8rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #2ecc71;
        color: white;
    }

    .btn-primary:hover {
        background: #27ae60;
    }

    .btn-secondary {
        background: #ecf0f1;
        color: #7f8c8d;
    }

    .btn-secondary:hover {
        background: #d5d8dc;
    }
</style>

@if(session('success'))
<div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
    {{ session('error') }}
</div>
@endif

<div class="loans-grid">
    @forelse($loans as $loan)
    @php
        $today = \Carbon\Carbon::now();
        $daysRemaining = $today->diffInDays($loan->due_date, false);
        $totalDays = $loan->loan_date->diffInDays($loan->due_date);
        $daysUsed = $loan->loan_date->diffInDays($today);
        $progress = $totalDays > 0 ? min(100, ($daysUsed / $totalDays) * 100) : 0;
        
        // Determine status class
        if ($loan->status == 'pending') {
            $statusClass = 'active';
            $statusText = '⏳ Menunggu Konfirmasi';
        } elseif ($loan->status == 'return_pending') {
            $statusClass = 'active';
            $statusText = '⏳ Menunggu Konfirmasi Pengembalian';
        } elseif ($loan->status == 'rejected') {
            $statusClass = 'overdue';
            $statusText = '✗ Ditolak';
        } elseif ($daysRemaining < 0) {
            $statusClass = 'overdue';
            $statusText = 'Terlambat';
        } elseif ($daysRemaining <= 3) {
            $statusClass = 'due-soon';
            $statusText = 'Jatuh Tempo Segera';
        } else {
            $statusClass = 'active';
            $statusText = 'Aktif';
        }
        
        $progressClass = $progress > 80 ? 'danger' : ($progress > 60 ? 'warning' : '');
    @endphp
    
    <div class="loan-card">
        <div class="loan-header">
            <div class="book-cover">
                <img src="{{ $loan->book->cover_image }}" alt="{{ $loan->book->title }}">
            </div>
            <div class="book-info">
                <div class="book-title">{{ $loan->book->title }}</div>
                <div class="book-author">{{ $loan->book->author }}</div>
                <span class="loan-status {{ $statusClass }}">{{ $statusText }}</span>
            </div>
        </div>
        <div class="loan-body">
            <div class="loan-detail">
                <span class="loan-detail-label">No. Peminjaman:</span>
                <span class="loan-detail-value">#{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="loan-detail">
                <span class="loan-detail-label">Tanggal Pinjam:</span>
                <span class="loan-detail-value">{{ $loan->loan_date->format('d M Y') }}</span>
            </div>
            <div class="loan-detail">
                <span class="loan-detail-label">Jatuh Tempo:</span>
                <span class="loan-detail-value {{ $daysRemaining < 0 ? 'due-date' : '' }}">
                    {{ $loan->due_date->format('d M Y') }}
                    @if($loan->status == 'approved')
                        @if($daysRemaining < 0)
                            ({{ abs($daysRemaining) }} hari terlambat)
                        @else
                            ({{ $daysRemaining }} hari lagi)
                        @endif
                    @endif
                </span>
            </div>
            
            @if($loan->status == 'approved')
                <div class="loan-detail">
                    <span class="loan-detail-label">Durasi Pinjam:</span>
                    <span class="loan-detail-value">{{ $totalDays }} hari</span>
                </div>
                
                @if($daysRemaining < 0)
                <div class="loan-detail">
                    <span class="loan-detail-label">Denda:</span>
                    <span class="loan-detail-value due-date">Rp {{ number_format(abs($daysRemaining) * 1000, 0, ',', '.') }}</span>
                </div>
                @endif
                
                <div class="progress-bar">
                    <div class="progress-fill {{ $progressClass }}" style="width: {{ $progress }}%"></div>
                </div>
                <div style="text-align: center; font-size: 0.85rem; color: {{ $progress > 80 ? '#e74c3c' : ($progress > 60 ? '#f39c12' : '#2ecc71') }}; font-weight: 600;">
                    @if($daysRemaining < 0)
                        Segera kembalikan!
                    @else
                        {{ round($progress) }}% waktu terpakai
                    @endif
                </div>
            @endif
            
            <div class="loan-footer">
                @if($loan->status == 'pending')
                    <a href="{{ route('user.loan.receipt', $loan->id) }}" class="btn btn-secondary">Lihat Bukti</a>
                @elseif($loan->status == 'approved')
                    <a href="{{ route('user.return.request', $loan->id) }}" class="btn btn-primary">Kembalikan</a>
                    <a href="{{ route('user.loan.receipt', $loan->id) }}" class="btn btn-secondary">Lihat Bukti</a>
                @elseif($loan->status == 'return_pending')
                    <span style="color: #7f8c8d; font-size: 0.9rem;">Menunggu konfirmasi pengembalian...</span>
                @elseif($loan->status == 'rejected')
                    <span style="color: #e74c3c; font-size: 0.9rem;">Peminjaman ditolak oleh petugas</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; text-align: center; padding: 3rem; background: white; border-radius: 15px;">
        <p style="color: #7f8c8d; font-size: 1.1rem; margin-bottom: 1rem;">📚 Belum ada peminjaman aktif</p>
        <a href="{{ route('user.catalog') }}" class="btn btn-primary" style="display: inline-block; text-decoration: none;">Jelajahi Katalog Buku</a>
    </div>
    @endforelse
</div>

<div style="margin-top: 2rem; padding: 1.5rem; background: #fff3cd; border-radius: 15px; border-left: 4px solid #f39c12;">
    <h4 style="color: #856404; margin-bottom: 0.5rem;">⚠️ Informasi Penting</h4>
    <p style="color: #856404; font-size: 0.9rem;">
        • Durasi peminjaman standar: 14 hari<br>
        • Perpanjangan maksimal: 1x (7 hari)<br>
        • Denda keterlambatan: Rp 1.000/hari<br>
        • Maksimal peminjaman: 3 buku
    </p>
</div>
@endsection
