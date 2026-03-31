<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku - Perpustakaan Digital</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #2c3e50;
        }

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            z-index: 1000;
        }

        nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
        }

        .btn-secondary {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 6rem 2rem 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

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

        .search-input {
            flex: 1;
            padding: 0.6rem 1.2rem;
            border: 2px solid #ecf0f1;
            border-radius: 25px;
            font-size: 0.95rem;
            min-width: 250px;
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
            border-color: #667eea;
            background: #667eea;
            color: white;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: white;
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

        .btn-borrow {
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            background: #667eea;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn-borrow:hover {
            background: #5568d3;
        }

        .btn-disabled {
            background: #ecf0f1;
            color: #95a5a6;
            cursor: not-allowed;
        }

        .login-notice {
            background: #fff3cd;
            color: #856404;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .login-notice a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .login-notice a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 2rem;
            }

            .filter-bar {
                flex-direction: column;
            }

            .search-input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                📚 Perpustakaan Digital
            </div>
            <div class="nav-buttons">
                <a href="/" class="btn btn-secondary">Beranda</a>
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="page-header">
            <h1>Katalog Buku</h1>
            <p>Jelajahi koleksi buku perpustakaan digital</p>
        </div>

        <div class="login-notice">
            Untuk meminjam buku, silakan <a href="{{ route('login') }}">login</a> atau <a href="{{ route('register') }}">daftar</a> terlebih dahulu
        </div>

        <div class="filter-bar">
            <input type="text" id="searchInput" placeholder="🔍 Cari judul buku atau pengarang..." class="search-input">
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
                        <span>📅 {{ $book->year }}</span>
                    </div>
                    <div class="book-footer">
                        <div class="rating">⭐ {{ number_format($book->rating, 1) }}</div>
                        <a href="{{ route('login') }}" class="btn-borrow">Login untuk Pinjam</a>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #7f8c8d;">
                <p>Belum ada buku tersedia.</p>
            </div>
            @endforelse
        </div>
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
</body>
</html>
