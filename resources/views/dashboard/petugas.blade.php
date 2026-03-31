@extends('layouts.petugas')

@section('title', 'Dashboard Petugas Perpustakaan')
@section('page-title')
Halo, {{ Auth::user()->name }}! 📚
@endsection
@section('page-subtitle', 'Kelola peminjaman dan pengembalian buku')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .stat-icon.cyan {
        background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
        color: white;
    }

    .stat-icon.orange {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        color: white;
    }

    .stat-icon.red {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 0.3rem;
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .view-all {
        color: #3498db;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .loan-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid #ecf0f1;
        border-radius: 10px;
        margin-bottom: 0.8rem;
        transition: all 0.3s ease;
    }

    .loan-item:hover {
        border-color: #3498db;
        background: #f8f9fa;
    }

    .loan-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .loan-content {
        flex: 1;
    }

    .loan-content h4 {
        font-size: 0.95rem;
        color: #2c3e50;
        margin-bottom: 0.3rem;
    }

    .loan-content p {
        font-size: 0.8rem;
        color: #7f8c8d;
    }

    .status {
        padding: 0.3rem 0.8rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status.active {
        background: #cce5ff;
        color: #004085;
    }

    .status.overdue {
        background: #f8d7da;
        color: #721c24;
    }

    .status.returned {
        background: #d4edda;
        color: #155724;
    }

    .notification-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #ecf0f1;
        transition: background 0.3s ease;
    }

    .notification-item:hover {
        background: #f8f9fa;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.2rem;
    }

    .notification-icon.info {
        background: #e3f2fd;
    }

    .notification-icon.warning {
        background: #fff3e0;
    }

    .notification-icon.success {
        background: #e8f5e9;
    }

    .notification-content h4 {
        font-size: 0.9rem;
        color: #2c3e50;
        margin-bottom: 0.3rem;
    }

    .notification-content p {
        font-size: 0.8rem;
        color: #7f8c8d;
    }

    .full-width-card {
        grid-column: 1 / -1;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        text-align: left;
        padding: 0.8rem;
        color: #7f8c8d;
        font-weight: 600;
        font-size: 0.85rem;
        border-bottom: 2px solid #ecf0f1;
    }

    .table td {
        padding: 1rem 0.8rem;
        border-bottom: 1px solid #ecf0f1;
        color: #2c3e50;
    }

    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $borrowedBooks }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
            <div class="stat-icon blue">📖</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $returnsToday }}</div>
                <div class="stat-label">Pengembalian Hari Ini</div>
            </div>
            <div class="stat-icon cyan">↩️</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $loansToday }}</div>
                <div class="stat-label">Peminjaman Hari Ini</div>
            </div>
            <div class="stat-icon orange">📚</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $overdueBooks }}</div>
                <div class="stat-label">Terlambat</div>
            </div>
            <div class="stat-icon red">⚠️</div>
        </div>
    </div>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Peminjaman Hari Ini</h2>
            <a href="{{ route('petugas.loans') }}" class="view-all">Lihat Semua →</a>
        </div>
        @forelse($todayLoans as $loan)
        <div class="loan-item">
            <div class="loan-icon">📘</div>
            <div class="loan-content">
                <h4>{{ $loan->book->title }}</h4>
                <p>Dipinjam oleh: {{ $loan->user->name }}</p>
            </div>
            <span class="status active">{{ ucfirst($loan->status) }}</span>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: #7f8c8d;">
            Belum ada peminjaman hari ini
        </div>
        @endforelse
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Peringatan</h2>
        </div>
        <div class="notification-item">
            <div class="notification-icon warning">⚠️</div>
            <div class="notification-content">
                <h4>Buku terlambat dikembalikan</h4>
                <p>"Perahu Kertas" - 3 hari terlambat</p>
            </div>
        </div>
        <div class="notification-item">
            <div class="notification-icon warning">⚠️</div>
            <div class="notification-content">
                <h4>Jatuh tempo besok</h4>
                <p>5 buku akan jatuh tempo besok</p>
            </div>
        </div>
        <div class="notification-item">
            <div class="notification-icon success">✅</div>
            <div class="notification-content">
                <h4>Pengembalian tepat waktu</h4>
                <p>"Supernova" dikembalikan hari ini</p>
            </div>
        </div>
        <div class="notification-item">
            <div class="notification-icon info">📚</div>
            <div class="notification-content">
                <h4>Stok buku menipis</h4>
                <p>"Laskar Pelangi" hanya tersisa 2 eksemplar</p>
            </div>
        </div>
    </div>

    <div class="card full-width-card">
        <div class="card-header">
            <h2 class="card-title">Riwayat Peminjaman Terbaru</h2>
            <a href="{{ route('petugas.loans') }}" class="view-all">Lihat Semua →</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Peminjam</th>
                    <th>Tanggal Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLoans as $loan)
                <tr>
                    <td>{{ $loan->book->title }}</td>
                    <td>{{ $loan->user->name }}</td>
                    <td>{{ $loan->loan_date->format('d M Y') }}</td>
                    <td>{{ $loan->due_date->format('d M Y') }}</td>
                    <td>
                        @if($loan->status == 'pending')
                            <span class="status" style="background: #fff3cd; color: #856404;">Pending</span>
                        @elseif($loan->status == 'approved')
                            <span class="status active">Dipinjam</span>
                        @elseif($loan->status == 'returned')
                            <span class="status returned">Dikembalikan</span>
                        @else
                            <span class="status overdue">{{ ucfirst($loan->status) }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #7f8c8d;">Belum ada data peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
