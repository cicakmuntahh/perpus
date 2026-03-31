@extends('layouts.admin')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('page-subtitle', 'Kelola anggota perpustakaan')

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
    .table-container {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th {
        text-align: left;
        padding: 1rem;
        color: #7f8c8d;
        font-weight: 600;
        border-bottom: 2px solid #ecf0f1;
    }

    .table td {
        padding: 1rem;
        border-bottom: 1px solid #ecf0f1;
        color: #2c3e50;
    }

    .badge {
        padding: 0.3rem 0.8rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .btn-delete {
        padding: 0.5rem 1rem;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }

    .btn-delete:hover {
        background: #c0392b;
    }
</style>

<div class="table-container">
    <h2 style="margin-bottom: 1.5rem;">Daftar User ({{ $users->count() }})</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Terdaftar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <span class="badge badge-active">✓ Aktif</span>
                </td>
                <td>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #7f8c8d;">
                    Belum ada user terdaftar
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 2rem; padding: 1.5rem; background: #fff3cd; border-radius: 15px; border-left: 4px solid #f39c12;">
    <h4 style="color: #856404; margin-bottom: 0.5rem;">ℹ️ Informasi</h4>
    <p style="color: #856404; font-size: 0.9rem; margin: 0;">
        User hanya bisa dihapus jika tidak memiliki peminjaman aktif. User yang dihapus tidak dapat dikembalikan.
    </p>
</div>
@endsection
