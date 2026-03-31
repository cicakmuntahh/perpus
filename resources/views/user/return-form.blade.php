@extends('layouts.user')

@section('title', 'Form Pengembalian Buku')
@section('page-title', 'Form Pengembalian Buku')
@section('page-subtitle', 'Konfirmasi pengembalian buku Anda')

@section('content')
<style>
    .form-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .return-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .book-info {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .book-cover {
        width: 100px;
        height: 140px;
        border-radius: 8px;
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
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
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .book-author {
        color: #7f8c8d;
        margin-bottom: 1rem;
    }

    .loan-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .info-item {
        font-size: 0.85rem;
    }

    .info-label {
        color: #7f8c8d;
    }

    .info-value {
        color: #2c3e50;
        font-weight: 600;
    }

    .warning-box {
        background: #fff3cd;
        border-left: 4px solid #f39c12;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .warning-box h4 {
        color: #856404;
        margin-bottom: 0.5rem;
    }

    .warning-box p {
        color: #856404;
        margin: 0;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
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
        text-decoration: none;
        text-align: center;
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
</style>

<div class="form-container">
    <div class="return-card">
        <h3 style="margin-bottom: 1.5rem; color: #2c3e50;">Konfirmasi Pengembalian</h3>
        
        <div class="book-info">
            <div class="book-cover">
                <img src="{{ $loan->book->cover_image }}" alt="{{ $loan->book->title }}">
            </div>
            <div class="book-details">
                <div class="book-title">{{ $loan->book->title }}</div>
                <div class="book-author">{{ $loan->book->author }}</div>
                <div class="loan-info">
                    <div class="info-item">
                        <div class="info-label">No. Peminjaman</div>
                        <div class="info-value">#{{ str_pad($loan->id, 6, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Pinjam</div>
                        <div class="info-value">{{ $loan->loan_date->format('d M Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jatuh Tempo</div>
                        <div class="info-value">{{ $loan->due_date->format('d M Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Durasi Pinjam</div>
                        <div class="info-value">{{ $loan->loan_date->diffInDays($loan->due_date) }} hari</div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $today = \Carbon\Carbon::now();
            $daysLate = $today->diffInDays($loan->due_date, false);
        @endphp

        @if($daysLate < 0)
        <div class="warning-box">
            <h4>⚠️ Keterlambatan Pengembalian</h4>
            <p>Buku ini terlambat {{ abs($daysLate) }} hari. Denda yang harus dibayar: <strong>Rp {{ number_format(abs($daysLate) * 1000, 0, ',', '.') }}</strong></p>
            <p style="margin-top: 0.5rem;">Silakan bayar denda di petugas perpustakaan saat mengembalikan buku.</p>
        </div>
        @else
        <div style="background: #d4edda; border-left: 4px solid #2ecc71; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <h4 style="color: #155724; margin-bottom: 0.5rem;">✓ Pengembalian Tepat Waktu</h4>
            <p style="color: #155724; margin: 0;">Terima kasih telah mengembalikan buku tepat waktu!</p>
        </div>
        @endif

        <div style="background: #e8f5e9; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
            <h4 style="color: #2c3e50; margin-bottom: 0.5rem;">📋 Prosedur Pengembalian</h4>
            <ol style="margin: 0; padding-left: 1.5rem; color: #2c3e50;">
                <li>Klik tombol "Ajukan Pengembalian" di bawah</li>
                <li>Bawa buku ke perpustakaan</li>
                <li>Serahkan buku ke petugas</li>
                <li>Petugas akan mengkonfirmasi pengembalian</li>
                @if($daysLate < 0)
                <li>Bayar denda keterlambatan (jika ada)</li>
                @endif
            </ol>
        </div>

        <form action="{{ route('user.return.submit', $loan->id) }}" method="POST">
            @csrf
            <div class="form-actions">
                <a href="{{ route('user.loans') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin mengajukan pengembalian buku ini?')">Ajukan Pengembalian</button>
            </div>
        </form>
    </div>
</div>
@endsection
