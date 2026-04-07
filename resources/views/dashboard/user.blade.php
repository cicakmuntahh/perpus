@extends('layouts.user')

@section('title', 'Perpustakaan Digital')
@section('page-title')
Selamat Datang, {{ Auth::user()->name }}! 📚
@endsection
@section('page-subtitle', 'Temukan dan pinjam buku favorit Anda')

@section('content')
<style>
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .action-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .action-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1rem;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }

    .action-icon.green {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    }

    .action-icon.blue {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .action-icon.purple {
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
    }

    .action-icon.orange {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    .action-card h3 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .action-card p {
        color: #7f8c8d;
        font-size: 0.85rem;
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
        color: #2ecc71;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .book-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border: 1px solid #ecf0f1;
        border-radius: 10px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .book-item:hover {
        border-color: #2ecc71;
        background: #f8f9fa;
    }

    .book-cover {
        width: 80px;
        height: 110px;
        border-radius: 8px;
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-content {
        flex: 1;
    }

    .book-content h4 {
        font-size: 1rem;
        color: #2c3e50;
        margin-bottom: 0.3rem;
    }

    .book-author {
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-bottom: 0.5rem;
    }

    .book-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.75rem;
        color: #95a5a6;
    }

    .loan-item {
        padding: 1rem;
        border-left: 4px solid #2ecc71;
        background: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .loan-item h4 {
        font-size: 0.95rem;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .loan-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #7f8c8d;
    }

    .due-date {
        color: #e74c3c;
        font-weight: 600;
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
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.2rem;
    }

    .activity-icon.green {
        background: #d4edda;
    }

    .activity-icon.blue {
        background: #cce5ff;
    }

    .activity-icon.purple {
        background: #e8daef;
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

<div class="quick-actions">
    <a href="{{ route('user.catalog') }}" class="action-card">
        <div class="action-icon green">📚</div>
        <h3>Katalog Buku</h3>
        <p>Jelajahi koleksi buku</p>
    </a>
    <a href="{{ route('user.loans') }}" class="action-card">
        <div class="action-icon blue">📖</div>
        <h3>Peminjaman Saya</h3>
        <p>Lihat buku yang dipinjam</p>
    </a>
    <a href="{{ route('user.history') }}" class="action-card">
        <div class="action-icon orange">📜</div>
        <h3>Riwayat</h3>
        <p>Riwayat peminjaman</p>
    </a>
</div>

<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;">{{ $totalBorrowed }}</div>
                <div style="color: #7f8c8d; font-size: 0.9rem;">Total Peminjaman</div>
            </div>
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#3498db,#2980b9);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">📋</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;">{{ $activeLoan }}</div>
                <div style="color: #7f8c8d; font-size: 0.9rem;">Sedang Dipinjam</div>
            </div>
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#f39c12,#e67e22);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">📖</div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 0;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 2rem; font-weight: bold; color: #2c3e50;">{{ $totalReturned }}</div>
                <div style="color: #7f8c8d; font-size: 0.9rem;">Sudah Dikembalikan</div>
            </div>
            <div style="width:50px;height:50px;border-radius:12px;background:linear-gradient(135deg,#2ecc71,#27ae60);display:flex;align-items:center;justify-content:center;font-size:1.5rem;">✅</div>
        </div>
    </div>
</div>

<div class="content-grid">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Buku Rekomendasi</h2>
            <a href="{{ route('user.catalog') }}" class="view-all">Lihat Semua →</a>
        </div>
        @forelse($recommendedBooks as $book)
        <div class="book-item">
            <div class="book-cover">
                <img src="{{ $book->cover_image }}" alt="{{ $book->title }}">
            </div>
            <div class="book-content">
                <h4>{{ $book->title }}</h4>
                <div class="book-author">{{ $book->author }}</div>
                <div class="book-meta">
                    <span>📚 {{ $book->category->name }}</span>
                    <span>⭐ {{ number_format($book->rating, 1) }}</span>
                    @if($book->available > 0)
                        <span>✅ Tersedia</span>
                    @else
                        <span>❌ Habis</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 2rem; color: #7f8c8d;">
            Belum ada buku tersedia
        </div>
        @endforelse
    </div>

    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h2 class="card-title">Sedang Dipinjam</h2>
            </div>
            @forelse($currentLoans as $loan)
            <div class="loan-item">
                <h4>{{ $loan->book->title }}</h4>
                <div class="loan-meta">
                    <span>Dipinjam: {{ $loan->loan_date->format('d M Y') }}</span>
                    <span class="due-date">Jatuh tempo: {{ $loan->due_date->format('d M Y') }}</span>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2rem; color: #7f8c8d;">
                Belum ada peminjaman aktif
            </div>
            @endforelse
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Aktivitas Terbaru</h2>
            </div>
            @php
                $recentUserLoans = App\Models\Loan::with('book')
                    ->where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->take(4)->get();
            @endphp
            @forelse($recentUserLoans as $activity)
            <div class="activity-item">
                <div class="activity-icon {{ in_array($activity->status, ['approved','return_pending']) ? 'blue' : ($activity->status == 'returned' ? 'green' : 'purple') }}">
                    @if($activity->status == 'pending') 📋
                    @elseif($activity->status == 'approved') 📖
                    @elseif($activity->status == 'return_pending') ↩️
                    @elseif($activity->status == 'returned') ✅
                    @else ❌
                    @endif
                </div>
                <div class="activity-content">
                    <h4>{{ $activity->book->title }}</h4>
                    <p>
                        @if($activity->status == 'pending') Menunggu persetujuan
                        @elseif($activity->status == 'approved') Sedang dipinjam
                        @elseif($activity->status == 'return_pending') Menunggu konfirmasi kembali
                        @elseif($activity->status == 'returned') Sudah dikembalikan
                        @else Ditolak
                        @endif
                        · {{ $activity->created_at->diffForHumans() }}
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
</div>
@endsection
