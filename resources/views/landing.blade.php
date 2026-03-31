<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
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

        /* Header */
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

        .nav-center {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: #2c3e50;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .signup-btn {
            padding: 0.6rem 1.5rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .signup-btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        /* Sections */
        section {
            min-height: 100vh;
            padding: 6rem 2rem 2rem;
            scroll-margin-top: 80px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Home Section */
        #home {
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .hero-content {
            text-align: center;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: white;
            color: #667eea;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }

        /* About Section */
        #about {
            background: white;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 3rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.3rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #7f8c8d;
            line-height: 1.6;
        }

        /* Library Section */
        #library {
            background: #f8f9fa;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .book-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-5px);
        }

        .book-cover {
            width: 100%;
            height: 300px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            overflow: hidden;
            position: relative;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            background: white;
        }

        .book-info {
            padding: 1.5rem;
        }

        .book-info h3 {
            font-size: 1.1rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .book-info p {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .book-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #95a5a6;
        }

        /* Footer */
        footer {
            background: #2c3e50;
            color: white;
            padding: 2rem;
            text-align: center;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-content p {
                font-size: 1rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .section-title {
                font-size: 2rem;
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
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#library">Library</a></li>
                </ul>
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="signup-btn">Dashboard</a>
                    @elseif(Auth::user()->role === 'petugas')
                        <a href="{{ route('petugas.dashboard') }}" class="signup-btn">Dashboard</a>
                    @else
                        <a href="{{ route('user.dashboard') }}" class="signup-btn">Dashboard</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="signup-btn">Login</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Home Section -->
    <section id="home">
        <div class="container">
            <div class="hero-content">
                <h1>Selamat Datang di Perpustakaan Digital</h1>
                <p>Akses ribuan buku digital kapan saja, di mana saja</p>
                <div class="hero-buttons">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary">Daftar Sekarang</a>
                    @endguest
                    <a href="#library" class="btn btn-secondary">Jelajahi Koleksi</a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about">
        <div class="container">
            <h2 class="section-title">Tentang Kami</h2>
            <p style="text-align: center; color: #7f8c8d; max-width: 800px; margin: 0 auto 3rem;">
                Perpustakaan Digital adalah platform modern yang memudahkan Anda untuk mengakses, meminjam, 
                dan membaca buku secara online. Kami berkomitmen untuk menyediakan koleksi buku berkualitas 
                untuk mendukung pembelajaran dan pengembangan diri Anda.
            </p>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Koleksi Lengkap</h3>
                    <p>Ribuan buku dari berbagai kategori tersedia untuk Anda. Dari fiksi hingga non-fiksi, semua ada di sini.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h3>Mudah Dicari</h3>
                    <p>Sistem pencarian canggih memudahkan Anda menemukan buku yang diinginkan dengan cepat.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Akses Kapan Saja</h3>
                    <p>Baca buku favorit Anda di mana saja dan kapan saja melalui perangkat apapun.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⭐</div>
                    <h3>Rekomendasi Personal</h3>
                    <p>Dapatkan rekomendasi buku yang sesuai dengan minat dan riwayat bacaan Anda.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔒</div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Data Anda aman dengan sistem keamanan tingkat tinggi yang kami terapkan.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <h3>Komunitas Aktif</h3>
                    <p>Bergabung dengan komunitas pembaca dan diskusikan buku favorit Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Library Section -->
    <section id="library">
        <div class="container">
            <h2 class="section-title">Koleksi Buku Populer</h2>
            <p style="text-align: center; color: #7f8c8d; margin-bottom: 3rem;">
                Jelajahi koleksi buku terpopuler kami
            </p>

            <div class="books-grid">
                @forelse($popularBooks as $book)
                <div class="book-card">
                    <div class="book-cover">
                        <img src="{{ $book->cover_image }}" alt="{{ $book->title }}">
                    </div>
                    <div class="book-info">
                        <h3>{{ $book->title }}</h3>
                        <p>{{ $book->author }}</p>
                        <div class="book-meta">
                            <span>📚 {{ $book->category->name }}</span>
                            <span>⭐ {{ number_format($book->rating, 1) }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #7f8c8d;">
                    <p>Belum ada buku tersedia.</p>
                </div>
                @endforelse
            </div>

            <div style="text-align: center; margin-top: 3rem;">
                <a href="{{ route('catalog') }}" class="btn btn-primary" style="display: inline-block; background: #667eea; color: white;">
                    Lihat Semua Koleksi
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2026 Perpustakaan Digital. All rights reserved.</p>
            <p style="margin-top: 0.5rem; opacity: 0.8;">Membaca adalah jendela dunia 📚</p>
        </div>
    </footer>

    <script>
        // Smooth scroll untuk navigasi
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Highlight active nav link on scroll
        window.addEventListener('scroll', () => {
            let current = '';
            const sections = document.querySelectorAll('section');
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });

            document.querySelectorAll('.nav-links a').forEach(link => {
                link.style.color = '#2c3e50';
                if (link.getAttribute('href') === `#${current}`) {
                    link.style.color = '#667eea';
                }
            });
        });
    </script>
</body>
</html>
