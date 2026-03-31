<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Petugas Dashboard')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .logo {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }

        .logo h2 {
            font-size: 1.5rem;
            margin-bottom: 0.3rem;
        }

        .logo p {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .menu {
            list-style: none;
        }

        .menu-item {
            padding: 1rem 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-left: 3px solid transparent;
            text-decoration: none;
            color: white;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
        }

        .menu-icon {
            font-size: 1.2rem;
        }
        
        .menu-item span:last-child {
            flex: 1;
        }

        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem;
        }

        .top-bar {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .welcome h1 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 0.3rem;
        }

        .welcome p {
            color: #7f8c8d;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .user-info h3 {
            font-size: 1rem;
            color: #2c3e50;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            background: #3498db;
            color: white;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .logout-btn {
            padding: 0.6rem 1.5rem;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
        }

        .card-title {
            font-size: 1.2rem;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .main-content {
                margin-left: 70px;
            }

            .menu-item span:last-child {
                display: none;
            }

            .logo h2, .logo p {
                display: none;
            }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="logo">
            <h2>Petugas Perpustakaan</h2>
            <p>Manajemen Peminjaman</p>
        </div>
        <ul class="menu">
            <a href="{{ route('petugas.dashboard') }}" class="menu-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                <span class="menu-icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('petugas.books') }}" class="menu-item {{ request()->routeIs('petugas.books') ? 'active' : '' }}">
                <span class="menu-icon">📚</span>
                <span>Kelola Buku</span>
            </a>
            <a href="{{ route('petugas.categories') }}" class="menu-item {{ request()->routeIs('petugas.categories') ? 'active' : '' }}">
                <span class="menu-icon">🏷️</span>
                <span>Kelola Kategori</span>
            </a>
            <a href="{{ route('petugas.loans') }}" class="menu-item {{ request()->routeIs('petugas.loans') ? 'active' : '' }}">
                <span class="menu-icon">📖</span>
                <span>Peminjaman</span>
            </a>
            <a href="{{ route('petugas.reviews') }}" class="menu-item {{ request()->routeIs('petugas.reviews') ? 'active' : '' }}">
                <span class="menu-icon">⭐</span>
                <span>Ulasan</span>
            </a>
            <a href="{{ route('petugas.reports') }}" class="menu-item {{ request()->routeIs('petugas.reports') ? 'active' : '' }}">
                <span class="menu-icon">📄</span>
                <span>Laporan</span>
            </a>
        </ul>
        
        <div style="margin-top: auto; padding: 1rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.2);">
            <a href="/" style="display: block; padding: 0.8rem 1rem; text-decoration: none; color: white; font-size: 0.9rem; opacity: 0.8; transition: opacity 0.3s;">
                <span style="margin-right: 0.5rem;">←</span>
                <span>Kembali</span>
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div class="welcome">
                <h1>@yield('page-title', 'Halo')</h1>
                <p>@yield('page-subtitle', 'Anda memiliki tugas yang perlu diselesaikan hari ini')</p>
            </div>
            <div class="user-profile">
                <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div class="user-info">
                    <h3>{{ Auth::user()->name }}</h3>
                    <span class="badge">{{ strtoupper(Auth::user()->role) }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>

        @yield('content')
    </main>
</body>
</html>
