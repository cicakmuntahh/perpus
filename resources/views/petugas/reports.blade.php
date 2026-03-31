@extends('layouts.petugas')

@section('title', 'Laporan')
@section('page-title', 'Cetak Laporan')
@section('page-subtitle', 'Cetak dan unduh laporan perpustakaan')

@section('content')
<style>
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .report-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }

    .report-card:hover {
        transform: translateY(-5px);
    }

    .report-icon {
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

    .report-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .report-desc {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .btn-print {
        width: 100%;
        padding: 0.8rem;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-print:hover {
        background: #2980b9;
    }
</style>

<div class="reports-grid">
    <div class="report-card">
        <div class="report-icon">📚</div>
        <div class="report-title">Laporan Data Buku</div>
        <div class="report-desc">Cetak daftar semua buku yang tersedia di perpustakaan</div>
        <a href="{{ route('petugas.reports.books') }}" class="btn-print" style="display: block; text-align: center; text-decoration: none;">🖨️ Cetak Laporan</a>
    </div>

    <div class="report-card">
        <div class="report-icon">📖</div>
        <div class="report-title">Laporan Peminjaman</div>
        <div class="report-desc">Cetak data peminjaman buku dalam periode tertentu</div>
        <a href="{{ route('petugas.reports.loans') }}" class="btn-print" style="display: block; text-align: center; text-decoration: none;">🖨️ Cetak Laporan</a>
    </div>

    <div class="report-card">
        <div class="report-icon">↩️</div>
        <div class="report-title">Laporan Pengembalian</div>
        <div class="report-desc">Cetak data pengembalian buku dalam periode tertentu</div>
        <a href="{{ route('petugas.reports.returns') }}" class="btn-print" style="display: block; text-align: center; text-decoration: none;">🖨️ Cetak Laporan</a>
    </div>
</div>
@endsection
