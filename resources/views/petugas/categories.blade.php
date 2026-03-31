@extends('layouts.petugas')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')
@section('page-subtitle', 'Kelola kategori buku perpustakaan')

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
        margin-bottom: 2rem;
    }

    .btn-add {
        padding: 0.8rem 1.5rem;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-add:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .category-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .category-card:hover {
        transform: translateY(-5px);
    }

    .category-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .category-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .category-desc {
        color: #7f8c8d;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        min-height: 40px;
    }

    .category-count {
        color: #3498db;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .category-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        flex: 1;
        padding: 0.6rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .btn-edit {
        background: #3498db;
        color: white;
    }

    .btn-edit:hover {
        background: #2980b9;
    }

    .btn-delete {
        background: #e74c3c;
        color: white;
    }

    .btn-delete:hover {
        background: #c0392b;
    }
</style>

<div class="action-bar">
    <h2 style="margin: 0;">Daftar Kategori ({{ $categories->count() }})</h2>
    <button class="btn-add" onclick="openAddModal()">+ Tambah Kategori</button>
</div>

<div class="categories-grid">
    @php
        $icons = ['📚', '📖', '💡', '❤️', '🔬', '🎓', '🎨', '⚽', '🎵', '🍳'];
        $gradients = [
            'linear-gradient(135deg, #3498db 0%, #2980b9 100%)',
            'linear-gradient(135deg, #1abc9c 0%, #16a085 100%)',
            'linear-gradient(135deg, #f39c12 0%, #e67e22 100%)',
            'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)',
            'linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%)',
            'linear-gradient(135deg, #2ecc71 0%, #27ae60 100%)',
            'linear-gradient(135deg, #e91e63 0%, #c2185b 100%)',
            'linear-gradient(135deg, #34495e 0%, #2c3e50 100%)',
        ];
    @endphp

    @forelse($categories as $index => $category)
    <div class="category-card">
        <div class="category-icon" style="background: {{ $gradients[$index % count($gradients)] }};">
            {{ $icons[$index % count($icons)] }}
        </div>
        <div class="category-name">{{ $category->name }}</div>
        <div class="category-desc">{{ $category->description ?? 'Tidak ada deskripsi' }}</div>
        <div class="category-count">{{ $category->books_count }} Buku</div>
        <div class="category-actions">
            <button class="btn btn-edit" onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description) }}')">Edit</button>
            <form action="{{ route('petugas.categories.destroy', $category->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete" style="width: 100%;">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; text-align: center; padding: 3rem; background: white; border-radius: 15px;">
        <p style="color: #7f8c8d;">Belum ada kategori.</p>
    </div>
    @endforelse
</div>

<!-- Modal Tambah Kategori -->
<div id="addModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="max-width: 500px; margin: 100px auto; background: white; border-radius: 15px; padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Tambah Kategori Baru</h2>
        <form action="{{ route('petugas.categories.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Kategori *</label>
                <input type="text" name="name" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Deskripsi</label>
                <textarea name="description" rows="3" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;"></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeAddModal()" style="flex: 1; padding: 0.8rem; background: #ecf0f1; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="flex: 1; padding: 0.8rem; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="max-width: 500px; margin: 100px auto; background: white; border-radius: 15px; padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Edit Kategori</h2>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nama Kategori *</label>
                <input type="text" name="name" id="edit_name" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Deskripsi</label>
                <textarea name="description" id="edit_description" rows="3" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;"></textarea>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeEditModal()" style="flex: 1; padding: 0.8rem; background: #ecf0f1; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="flex: 1; padding: 0.8rem; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Update</button>
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

function openEditModal(id, name, description) {
    document.getElementById('editForm').action = '{{ route("petugas.categories.update", ":id") }}'.replace(':id', id);
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description || '';
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
