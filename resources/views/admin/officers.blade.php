@extends('layouts.admin')

@section('title', 'Kelola Petugas')
@section('page-title', 'Kelola Petugas')
@section('page-subtitle', 'Kelola petugas perpustakaan')

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
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .btn-add {
        padding: 0.8rem 1.5rem;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }

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
        background: #cce5ff;
        color: #004085;
    }

    .btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .btn-edit {
        background: #3498db;
        color: white;
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }
</style>

<div class="action-bar">
    <h2 style="margin: 0;">Daftar Petugas ({{ $officers->count() }})</h2>
    <button class="btn-add" onclick="openAddModal()">+ Tambah Petugas</button>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Terdaftar</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($officers as $index => $officer)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $officer->name }}</td>
                <td>{{ $officer->email }}</td>
                <td>{{ $officer->created_at->format('d M Y') }}</td>
                <td>
                    <span class="badge">PETUGAS</span>
                </td>
                <td>
                    <button class="btn btn-edit" onclick="openEditModal({{ $officer->id }}, '{{ addslashes($officer->name) }}', '{{ $officer->email }}')">Edit</button>
                    <form action="{{ route('admin.officers.destroy', $officer->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus petugas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: #7f8c8d;">
                    Belum ada petugas terdaftar
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Tambah Petugas -->
<div id="addModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="max-width: 500px; margin: 100px auto; background: white; border-radius: 15px; padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Tambah Petugas Baru</h2>
        <form action="{{ route('admin.officers.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Lengkap *</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email *</label>
                <input type="email" name="email" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Password *</label>
                <input type="password" name="password" required minlength="8" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" required minlength="8" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeAddModal()" style="flex: 1; padding: 0.8rem; background: #ecf0f1; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="flex: 1; padding: 0.8rem; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Petugas -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="max-width: 500px; margin: 100px auto; background: white; border-radius: 15px; padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Edit Petugas</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Lengkap *</label>
                <input type="text" name="name" id="edit_name" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Email *</label>
                <input type="email" name="email" id="edit_email" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" minlength="8" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" minlength="8" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeEditModal()" style="flex: 1; padding: 0.8rem; background: #ecf0f1; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="flex: 1; padding: 0.8rem; background: #667eea; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

function openEditModal(id, name, email) {
    document.getElementById('editForm').action = '{{ route("admin.officers.update", ":id") }}'.replace(':id', id);
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_email').value = email;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    if (event.target == addModal) {
        closeAddModal();
    }
    if (event.target == editModal) {
        closeEditModal();
    }
}
</script>
@endsection
