@extends('layouts.user')

@section('title', 'Bukti Peminjaman')
@section('page-title', 'Bukti Peminjaman Buku')
@section('page-subtitle', 'Simpan bukti ini sebagai referensi')

@section('content')
<style>
    .receipt-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .receipt-card {
        background: white;
        padding: 3rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        position: relative;
    }

    .receipt-header {
        text-align: center;
        padding-bottom: 2rem;
        border-bottom: 2px dashed #ecf0f1;
        margin-bottom: 2rem;
    }

    .receipt-logo {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .receipt-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .receipt-subtitle {
        color: #7f8c8d;
    }

    .receipt-number {
        background: #2ecc71;
        color: white;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        display: inline-block;
        margin-top: 1rem;
        font-weight: 600;
    }

    .status-badge {
        position: absolute;
        top: 2rem;
        right: 2rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .receipt-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
    }

    .info-section h3 {
        color: #2c3e50;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #ecf0f1;
    }

    .info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .info-label {
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    .info-value {
        color: #2c3e50;
        font-weight: 600;
        text-align: right;
    }

    .book-section {
        grid-column: 1 / -1;
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        padding: 2rem;
        border-radius: 10px;
        color: white;
        display: flex;
        gap: 2rem;
    }

    .book-cover {
        width: 120px;
        height: 160px;
        border-radius: 8px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
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
        margin-bottom: 0.5rem;
    }

    .book-author {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 1rem;
    }

    .book-meta {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.8rem;
    }

    .meta-item {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .receipt-footer {
        padding-top: 2rem;
        border-top: 2px dashed #ecf0f1;
        text-align: center;
    }

    .footer-note {
        background: #fff3cd;
        padding: 1rem;
        border-radius: 8px;
        color: #856404;
        margin-bottom: 1.5rem;
        text-align: left;
    }

    .footer-note h4 {
        margin-bottom: 0.5rem;
    }

    .footer-note ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .footer-note li {
        margin-bottom: 0.3rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn {
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
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

    @media print {
        .action-buttons {
            display: none;
        }
        .receipt-card {
            box-shadow: none;
        }
    }
</style>

<div class="receipt-container">
    <div class="receipt-card">
        @if($loan->status == 'pending')
            <span class="status-badge status-pending">⏳ Menunggu Konfirmasi</span>
        @elseif($loan->status == 'approved')
            <span class="status-badge status-approved">✓ Disetujui</span>
        @elseif($loan->status == 'rejected')
            <span class="status-badge status-rejected">✗ Ditolak</span>
        @endif

        <div class="receipt-header">
            <div class="receipt-logo">📚</div>
            <div class="receipt-title">Perpustakaan Digital</div>
            <div class="receipt-subtitle">Bukti Peminjaman Buku</div>
            <div class="receipt-number">#{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="receipt-body">
            <div class="info-section">
                <h3>👤 Data Peminjam</h3>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value">{{ $loan->user->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $loan->user->email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">ID Anggota</span>
                    <span class="info-value">#{{ str_pad($loan->user->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <div class="info-section">
                <h3>📅 Informasi Peminjaman</h3>
                <div class="info-row">
                    <span class="info-label">Tanggal Pinjam</span>
                    <span class="info-value">{{ $loan->loan_date->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jatuh Tempo</span>
                    <span class="info-value">{{ $loan->due_date->format('d M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Durasi</span>
                    <span class="info-value">{{ $loan->loan_date->diffInDays($loan->due_date) }} hari</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        @if($loan->status == 'pending')
                            Menunggu Konfirmasi
                        @elseif($loan->status == 'approved')
                            Disetujui
                        @elseif($loan->status == 'rejected')
                            Ditolak
                        @endif
                    </span>
                </div>
            </div>

            <div class="book-section">
                <div class="book-cover">
                    <img src="{{ $loan->book->cover_image }}" alt="{{ $loan->book->title }}">
                </div>
                <div class="book-details">
                    <div class="book-title">{{ $loan->book->title }}</div>
                    <div class="book-author">{{ $loan->book->author }}</div>
                    <div class="book-meta">
                        <div class="meta-item">📚 {{ $loan->book->category->name }}</div>
                        <div class="meta-item">📄 {{ $loan->book->pages }} halaman</div>
                        <div class="meta-item">ISBN: {{ $loan->book->isbn }}</div>
                        <div class="meta-item">⭐ {{ number_format($loan->book->rating, 1) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="receipt-footer">
            <div class="footer-note">
                <h4>⚠️ Perhatian:</h4>
                <ul>
                    <li>Harap kembalikan buku sesuai tanggal jatuh tempo</li>
                    <li>Denda keterlambatan: Rp 1.000/hari</li>
                    <li>Jaga kondisi buku dengan baik</li>
                    <li>Simpan bukti ini sebagai referensi</li>
                </ul>
            </div>

            <div class="action-buttons">
                <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak Bukti</button>
                <a href="{{ route('user.loans') }}" class="btn btn-secondary">Lihat Peminjaman Saya</a>
                <a href="{{ route('user.catalog') }}" class="btn btn-secondary">Kembali ke Katalog</a>
            </div>
        </div>
    </div>
</div>
@endsection
