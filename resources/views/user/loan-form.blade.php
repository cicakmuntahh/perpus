@extends('layouts.user')

@section('title', 'Form Peminjaman Buku')
@section('page-title', 'Form Peminjaman Buku')
@section('page-subtitle', 'Lengkapi data peminjaman Anda')

@section('content')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .book-preview {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        display: flex;
        gap: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .book-cover {
        width: 150px;
        height: 200px;
        border-radius: 10px;
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        flex-shrink: 0;
        overflow: hidden;
    }

    .book-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .book-details {
        flex: 1;
    }

    .book-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .book-author {
        color: #7f8c8d;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .book-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-bottom: 0.3rem;
    }

    .info-value {
        font-size: 1rem;
        color: #2c3e50;
        font-weight: 600;
    }

    .form-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #2c3e50;
        font-weight: 600;
    }

    .form-input {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #2ecc71;
    }

    .form-help {
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-top: 0.3rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        flex: 1;
        padding: 1rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: #2ecc71;
        color: white;
    }

    .btn-primary:hover {
        background: #27ae60;
    }

    .btn-secondary {
        background: #ecf0f1;
        color: #7f8c8d;
    }

    .btn-secondary:hover {
        background: #d5d8dc;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
    }

    .info-box {
        background: #e8f5e9;
        border-left: 4px solid #2ecc71;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .info-box h4 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .info-box ul {
        margin: 0;
        padding-left: 1.5rem;
        color: #2c3e50;
    }

    .info-box li {
        margin-bottom: 0.3rem;
    }
</style>

@if(session('error'))
<div class="alert alert-error">
    {{ session('error') }}
</div>
@endif

<div class="form-container">
    <div class="book-preview">
        <div class="book-cover">
            <img src="{{ $book->cover_image }}" alt="{{ $book->title }}">
        </div>
        <div class="book-details">
            <div class="book-title">{{ $book->title }}</div>
            <div class="book-author">{{ $book->author }}</div>
            <div class="book-info">
                <div class="info-item">
                    <span class="info-label">Kategori</span>
                    <span class="info-value">📚 {{ $book->category->name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">ISBN</span>
                    <span class="info-value">{{ $book->isbn }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Halaman</span>
                    <span class="info-value">{{ $book->pages }} halaman</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ketersediaan</span>
                    <span class="info-value" style="color: #2ecc71;">✓ {{ $book->available }} tersedia</span>
                </div>
            </div>
        </div>
    </div>

    <div class="info-box">
        <h4>📋 Ketentuan Peminjaman</h4>
        <ul>
            <li>Durasi peminjaman standar: 14 hari</li>
            <li>Buku harus dikembalikan sesuai tanggal jatuh tempo</li>
            <li>Denda keterlambatan: Rp 1.000/hari</li>
            <li>Maksimal peminjaman: 3 buku secara bersamaan</li>
            <li>Perpanjangan dapat dilakukan maksimal 1x (7 hari)</li>
        </ul>
    </div>

    <div class="form-card">
        <h3 style="margin-bottom: 1.5rem; color: #2c3e50;">Data Peminjaman</h3>
        
        <form action="{{ route('user.loan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            
            <div class="form-group">
                <label class="form-label">Nama Peminjam</label>
                <input type="text" class="form-input" value="{{ Auth::user()->name }}" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" value="{{ Auth::user()->email }}" readonly>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Pinjam *</label>
                <input type="date" name="loan_date" class="form-input" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                <div class="form-help">Tanggal mulai peminjaman buku</div>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Jatuh Tempo *</label>
                <input type="date" name="due_date" class="form-input" value="{{ date('Y-m-d', strtotime('+14 days')) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                <div class="form-help">Tanggal pengembalian buku (maksimal 14 hari dari tanggal pinjam)</div>
            </div>

            <div class="form-actions">
                <a href="{{ route('user.catalog') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loanDateInput = document.querySelector('input[name="loan_date"]');
    const dueDateInput = document.querySelector('input[name="due_date"]');

    loanDateInput.addEventListener('change', function() {
        const loanDate = new Date(this.value);
        const maxDueDate = new Date(loanDate);
        maxDueDate.setDate(maxDueDate.getDate() + 14);
        
        const defaultDueDate = new Date(loanDate);
        defaultDueDate.setDate(defaultDueDate.getDate() + 14);
        
        dueDateInput.min = loanDate.toISOString().split('T')[0];
        dueDateInput.max = maxDueDate.toISOString().split('T')[0];
        dueDateInput.value = defaultDueDate.toISOString().split('T')[0];
    });
});
</script>
@endsection
