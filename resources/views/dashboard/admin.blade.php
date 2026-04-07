@extends('layouts.admin')

@section('title', 'Dashboard Admin Perpustakaan')
@section('page-title')
Selamat Datang, {{ Auth::user()->name }}! 📚
@endsection
@section('page-subtitle', 'Kelola perpustakaan digital Anda dengan mudah')

@section('content')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

    .stat-icon.purple {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        color: white;
    }

    .stat-icon.orange {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
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
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .view-all {
        color: #667eea;
        text-decoration: none;
        font-size: 0.9rem;
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

    .status {
        padding: 0.3rem 0.8rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status.available {
        background: #d4edda;
        color: #155724;
    }

    .status.borrowed {
        background: #fff3cd;
        color: #856404;
    }

    .status.overdue {
        background: #f8d7da;
        color: #721c24;
    }

    .activity-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid #ecf0f1;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ecf0f1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .activity-content h4 {
        font-size: 0.9rem;
        color: #2c3e50;
        margin-bottom: 0.3rem;
    }

    .activity-content p {
        font-size: 0.8rem;
        color: #7f8c8d;
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
                <div class="stat-value">{{ $totalBooks }}</div>
                <div class="stat-label">Total Judul Buku</div>
            </div>
            <div class="stat-icon purple">📚</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $totalMembers }}</div>
                <div class="stat-label">Anggota Aktif</div>
            </div>
            <div class="stat-icon blue">👥</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $borrowedBooks }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
            <div class="stat-icon orange">📖</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-value">{{ $availableBooks }}</div>
                <div class="stat-label">Stok Tersedia</div>
            </div>
            <div class="stat-icon green">✅</div>
        </div>
    </div>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Buku Populer</h2>
            <a href="{{ route('admin.books') }}" class="view-all">Lihat Semua →</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Kategori</th>
                    <th>Rating</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularBooks as $book)
                <tr>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->category->name }}</td>
                    <td>⭐ {{ number_format($book->rating, 1) }}</td>
                    <td>
                        @if($book->available > 0)
                            <span class="status available">Tersedia ({{ $book->available }})</span>
                        @else
                            <span class="status borrowed">Habis</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #7f8c8d;">Belum ada data buku</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Aktivitas Terbaru</h2>
        </div>
        @forelse($recentActivities as $activity)
        <div class="activity-item">
            <div class="activity-icon">
                @if($activity->status == 'pending') 📋
                @elseif($activity->status == 'approved') 📖
                @elseif($activity->status == 'return_pending') ↩️
                @elseif($activity->status == 'returned') ✅
                @else ❌
                @endif
            </div>
            <div class="activity-content">
                <h4>{{ $activity->user->name }}</h4>
                <p>
                    @if($activity->status == 'pending') Mengajukan pinjam
                    @elseif($activity->status == 'approved') Sedang meminjam
                    @elseif($activity->status == 'return_pending') Mengajukan kembali
                    @elseif($activity->status == 'returned') Mengembalikan
                    @else Ditolak
                    @endif
                    "{{ $activity->book->title }}"
                </p>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: #7f8c8d;">
            Belum ada aktivitas
        </div>
        @endforelse
    </div>
</div>
@endsection
