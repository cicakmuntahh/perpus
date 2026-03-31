@extends('layouts.user')

@section('title', 'Katalog Buku')
@section('page-title', 'Katalog Buku')
@section('page-subtitle', 'Jelajahi koleksi buku perpustakaan digital')

@section('content')
<style>
    .filter-bar {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .filter-btn {
        padding: 0.6rem 1.2rem;
        border: 2px solid #ecf0f1;
        background: white;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #7f8c8d;
    }

    .filter-btn:hover, .filter-btn.active {
        border-color: #2ecc71;
        background: #2ecc71;
        color: white;
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }

    .book-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .book-card:hover {
        transform: translateY(-5px);
    }

    .book-cover {
        width: 100%;
        height: 320px;
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 5rem;
        position: relative;
        overflow: hidden;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-status {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: white;
    }

    .book-status.available {
        color: #2ecc71;
    }

    .book-status.borrowed {
        color: #e74c3c;
    }

    .book-content {
        padding: 1.5rem;
    }

    .book-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .book-author {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .book-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.85rem;
        color: #95a5a6;
        margin-bottom: 1rem;
    }

    .book-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #ecf0f1;
    }

    .rating {
        color: #f39c12;
        font-weight: 600;
    }

    .btn {
        padding: 0.6rem 1.2rem;
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

    .btn-disabled {
        background: #ecf0f1;
        color: #95a5a6;
        cursor: not-allowed;
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

<div class="filter-bar">
    <input type="text" id="searchInput" placeholder="🔍 Cari judul buku atau pengarang..." style="flex: 1; padding: 0.6rem 1.2rem; border: 2px solid #ecf0f1; border-radius: 25px; font-size: 0.95rem;">
</div>

<div class="filter-bar">
    <button class="filter-btn active" data-category="all">Semua</button>
    @foreach($categories as $category)
    <button class="filter-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
    @endforeach
</div>

<div class="books-grid">
    @forelse($books as $book)
    <div class="book-card" data-category="{{ $book->category_id }}" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author) }}">
        <div class="book-cover">
            @if($book->available > 0)
            <span class="book-status available">✓ Tersedia ({{ $book->available }})</span>
            @else
            <span class="book-status borrowed">✗ Habis</span>
            @endif
            <img src="{{ $book->cover_image }}" alt="{{ $book->title }}">
        </div>
        <div class="book-content">
            <div class="book-title">{{ $book->title }}</div>
            <div class="book-author">{{ $book->author }}</div>
            <div class="book-meta">
                <span>📚 {{ $book->category->name }}</span>
                <span>📄 {{ $book->pages }} hal</span>
            </div>
            <div class="book-footer">
                <div class="rating">⭐ {{ number_format($book->rating, 1) }}</div>
                @if($book->available > 0)
                <a href="{{ route('user.loan.create', $book->id) }}" class="btn btn-primary">Pinjam</a>
                @else
                <button class="btn btn-disabled" disabled>Tidak Tersedia</button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #7f8c8d;">
        <p>Belum ada buku tersedia.</p>
    </div>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const bookCards = document.querySelectorAll('.book-card');
    const searchInput = document.getElementById('searchInput');
    let currentCategory = 'all';

    // Filter by category
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            currentCategory = this.dataset.category;
            filterBooks();
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        filterBooks();
    });

    function filterBooks() {
        const searchTerm = searchInput.value.toLowerCase();

        bookCards.forEach(card => {
            const title = card.dataset.title;
            const author = card.dataset.author;
            const category = card.dataset.category;

            const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
            const matchesCategory = currentCategory === 'all' || category === currentCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
});
</script>
@endsection
