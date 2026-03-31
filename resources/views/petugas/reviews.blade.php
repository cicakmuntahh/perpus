@extends('layouts.petugas')

@section('title', 'Ulasan Buku')
@section('page-title', 'Ulasan Buku')
@section('page-subtitle', 'Lihat semua ulasan dari pengguna')

@section('content')

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

<style>
    .reviews-grid {
        display: grid;
        gap: 1.5rem;
    }

    .review-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 1.5rem;
    }

    .book-cover {
        width: 100px;
        height: 140px;
        border-radius: 8px;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        overflow: hidden;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: white;
    }

    .review-content {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
    }

    .book-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.3rem;
    }

    .book-author {
        font-size: 0.9rem;
        color: #7f8c8d;
    }

    .rating-stars {
        font-size: 1.3rem;
        color: #f39c12;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-top: 0.5rem;
        padding-top: 0.8rem;
        border-top: 1px solid #ecf0f1;
    }

    .reviewer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }

    .reviewer-details h4 {
        font-size: 0.95rem;
        color: #2c3e50;
        margin-bottom: 0.2rem;
    }

    .reviewer-details p {
        font-size: 0.8rem;
        color: #7f8c8d;
    }

    .review-comment {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        color: #2c3e50;
        line-height: 1.6;
        margin-top: 0.5rem;
        min-height: 60px;
        display: flex;
        align-items: center;
    }

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
    }

    .stat-value {
        font-size: 2rem;
        font-weight: bold;
        color: #3498db;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 0.9rem;
    }
</style>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $reviews->count() }}</div>
        <div class="stat-label">Total Ulasan</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($reviews->avg('rating'), 1) }}</div>
        <div class="stat-label">Rating Rata-rata</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $reviews->where('rating', 5)->count() }}</div>
        <div class="stat-label">Ulasan Bintang 5</div>
    </div>
</div>

<div class="card">
    <h2 class="card-title">Semua Ulasan</h2>
    
    <div class="reviews-grid">
        @forelse($reviews as $review)
        <div class="review-card">
            <div class="book-cover">
                <img src="{{ $review->book->cover_image }}" alt="{{ $review->book->title }}">
            </div>
            <div class="review-content">
                <div class="review-header">
                    <div>
                        <div class="book-title">{{ $review->book->title }}</div>
                        <div class="book-author">{{ $review->book->author }}</div>
                    </div>
                    <div class="rating-stars">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $review->rating)
                                ⭐
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                </div>
                
                <div class="review-comment">
                    {{ $review->comment ?? 'Tidak ada komentar' }}
                </div>
                
                <div class="reviewer-info">
                    <div class="reviewer-avatar">{{ strtoupper(substr($review->user->name, 0, 1)) }}</div>
                    <div class="reviewer-details">
                        <h4>{{ $review->user->name }}</h4>
                        <p>{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                    <form action="{{ route('petugas.reviews.destroy', $review->id) }}" method="POST" style="margin-left: auto;" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 0.5rem 1rem; background: #e74c3c; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.85rem;">🗑️ Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="text-align: center; padding: 3rem; color: #7f8c8d;">
            Belum ada ulasan dari pengguna
        </div>
        @endforelse
    </div>
</div>
@endsection
