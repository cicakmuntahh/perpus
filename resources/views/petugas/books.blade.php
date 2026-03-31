@extends('layouts.petugas')

@section('title', 'Kelola Buku')
@section('page-title', 'Kelola Buku')
@section('page-subtitle', 'Kelola koleksi buku perpustakaan')

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

@if($errors->any())
<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
    <strong>Error:</strong>
    <ul style="margin: 0.5rem 0 0 1.5rem;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
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
        height: 350px;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
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
        object-fit: contain;
        background: white;
    }

    .book-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        background: white;
    }

    .book-badge.available {
        color: #2ecc71;
    }

    .book-content {
        padding: 1.5rem;
    }

    .book-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .book-author {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .book-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #95a5a6;
        margin-bottom: 1rem;
    }

    .book-footer {
        display: flex;
        gap: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid #ecf0f1;
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

    .btn-primary {
        background: #3498db;
        color: white;
    }

    .btn-danger {
        background: #e74c3c;
        color: white;
    }
</style>

<div class="action-bar">
    <h2 style="margin: 0;">Daftar Buku ({{ $books->count() }} judul)</h2>
    <button class="btn-add" onclick="openAddModal()">+ Tambah Buku</button>
</div>

<div class="books-grid">
    @forelse($books as $book)
    <div class="book-card">
        <div class="book-cover">
            @if($book->available > 0)
            <span class="book-badge available">✓ Tersedia ({{ $book->available }})</span>
            @else
            <span class="book-badge" style="color: #e74c3c;">✗ Habis</span>
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
            <div class="book-meta">
                <span>Stok: {{ $book->stock }}</span>
                <span>Tersedia: {{ $book->available }}</span>
            </div>
            <div class="book-footer">
                <button class="btn btn-primary" 
                    data-id="{{ $book->id }}"
                    data-title="{{ $book->title }}"
                    data-author="{{ $book->author }}"
                    data-category="{{ $book->category_id }}"
                    data-isbn="{{ $book->isbn }}"
                    data-publisher="{{ $book->publisher }}"
                    data-year="{{ $book->year }}"
                    data-pages="{{ $book->pages }}"
                    data-stock="{{ $book->stock }}"
                    data-cover="{{ $book->cover_image }}"
                    onclick="openEditModalSafe(this)">Edit</button>
                <form action="{{ route('petugas.books.destroy', $book->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus buku ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div style="grid-column: 1/-1; text-align: center; padding: 3rem; background: white; border-radius: 15px;">
        <p style="color: #7f8c8d;">Belum ada buku tersedia.</p>
    </div>
    @endforelse
</div>
@endsection

<!-- Modal Tambah Buku -->
<div id="addModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="max-width: 600px; margin: 50px auto; background: white; border-radius: 15px; padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Tambah Buku Baru</h2>
        <form action="{{ route('petugas.books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Judul Buku *</label>
                <input type="text" name="title" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Pengarang *</label>
                <input type="text" name="author" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kategori *</label>
                <select name="category_id" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">ISBN *</label>
                <input type="text" name="isbn" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Penerbit *</label>
                <input type="text" name="publisher" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tahun *</label>
                    <input type="number" name="year" required min="1900" max="{{ date('Y') }}" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Halaman *</label>
                    <input type="number" name="pages" required min="1" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Stok *</label>
                <input type="number" name="stock" required min="1" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Cover Buku</label>
                <input type="file" name="cover_image" accept="image/*" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
                <small style="color: #7f8c8d; display: block; margin-top: 0.3rem;">Format: JPG, PNG, GIF (Max: 2MB)</small>
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeAddModal()" style="flex: 1; padding: 0.8rem; background: #ecf0f1; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="flex: 1; padding: 0.8rem; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Buku -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; overflow-y: auto;">
    <div style="max-width: 600px; margin: 50px auto; background: white; border-radius: 15px; padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Edit Buku</h2>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Judul Buku *</label>
                <input type="text" name="title" id="edit_title" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Pengarang *</label>
                <input type="text" name="author" id="edit_author" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kategori *</label>
                <select name="category_id" id="edit_category_id" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">ISBN *</label>
                <input type="text" name="isbn" id="edit_isbn" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Penerbit *</label>
                <input type="text" name="publisher" id="edit_publisher" required style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Tahun *</label>
                    <input type="number" name="year" id="edit_year" required min="1900" max="{{ date('Y') }}" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Halaman *</label>
                    <input type="number" name="pages" id="edit_pages" required min="1" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Stok *</label>
                <input type="number" name="stock" id="edit_stock" required min="1" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Cover Buku Saat Ini</label>
                <img id="edit_current_cover" src="" alt="Current Cover" style="max-width: 150px; max-height: 200px; border-radius: 8px; margin-bottom: 0.5rem;">
            </div>
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Ganti Cover Buku (Opsional)</label>
                <input type="file" name="cover_image" accept="image/*" style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px;">
                <small style="color: #7f8c8d; display: block; margin-top: 0.3rem;">Format: JPG, PNG, GIF (Max: 2MB). Kosongkan jika tidak ingin mengganti.</small>
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

function openEditModal(id, title, author, categoryId, isbn, publisher, year, pages, stock, coverImage) {
    document.getElementById('editForm').action = '{{ route("petugas.books.update", ":id") }}'.replace(':id', id);
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_author').value = author;
    document.getElementById('edit_category_id').value = categoryId;
    document.getElementById('edit_isbn').value = isbn;
    document.getElementById('edit_publisher').value = publisher;
    document.getElementById('edit_year').value = year;
    document.getElementById('edit_pages').value = pages;
    document.getElementById('edit_stock').value = stock;
    document.getElementById('edit_cover_image').value = coverImage;
    document.getElementById('editModal').style.display = 'block';
}

function openEditModalSafe(button) {
    const id = button.getAttribute('data-id');
    const title = button.getAttribute('data-title');
    const author = button.getAttribute('data-author');
    const categoryId = button.getAttribute('data-category');
    const isbn = button.getAttribute('data-isbn');
    const publisher = button.getAttribute('data-publisher');
    const year = button.getAttribute('data-year');
    const pages = button.getAttribute('data-pages');
    const stock = button.getAttribute('data-stock');
    const coverImage = button.getAttribute('data-cover');
    
    document.getElementById('editForm').action = '{{ route("petugas.books.update", ":id") }}'.replace(':id', id);
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_author').value = author;
    document.getElementById('edit_category_id').value = categoryId;
    document.getElementById('edit_isbn').value = isbn;
    document.getElementById('edit_publisher').value = publisher;
    document.getElementById('edit_year').value = year;
    document.getElementById('edit_pages').value = pages;
    document.getElementById('edit_stock').value = stock;
    document.getElementById('edit_current_cover').src = coverImage;
    document.getElementById('editModal').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Close modal when clicking outside
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