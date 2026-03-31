@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')
@section('page-title', 'Kelola Peminjaman & Pengembalian')
@section('page-subtitle', 'Konfirmasi peminjaman dan pengembalian buku')

@section('content')
<style>
    .tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .tab {
        padding: 0.8rem 1.5rem;
        background: white;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        color: #7f8c8d;
        transition: all 0.3s ease;
    }

    .tab.active {
        background: #667eea;
        color: white;
        border-color: #667eea;
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

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-returned {
        background: #cce5ff;
        color: #004085;
    }

    .btn-action {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .btn-approve {
        background: #2ecc71;
        color: white;
    }

    .btn-reject {
        background: #e74c3c;
        color: white;
    }
</style>

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

<div class="tabs">
    <div class="tab active" data-status="pending">Menunggu Konfirmasi ({{ $loans->where('status', 'pending')->count() }})</div>
    <div class="tab" data-status="return_pending">Pengembalian ({{ $loans->where('status', 'return_pending')->count() }})</div>
    <div class="tab" data-status="approved">Sedang Dipinjam ({{ $loans->where('status', 'approved')->count() }})</div>
    <div class="tab" data-status="all">Semua ({{ $loans->count() }})</div>
</div>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="loansTableBody">
            @forelse($loans as $index => $loan)
            <tr data-status="{{ $loan->status }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $loan->user->name }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ $loan->loan_date->format('d M Y') }}</td>
                <td>{{ $loan->due_date->format('d M Y') }}</td>
                <td>
                    @if($loan->status == 'pending')
                        <span class="status-badge status-pending">⏳ Menunggu</span>
                    @elseif($loan->status == 'approved')
                        <span class="status-badge status-approved">✓ Dipinjam</span>
                    @elseif($loan->status == 'return_pending')
                        <span class="status-badge status-pending">🔄 Pengembalian</span>
                    @elseif($loan->status == 'returned')
                        <span class="status-badge status-returned">✓ Dikembalikan</span>
                    @elseif($loan->status == 'rejected')
                        <span class="status-badge" style="background: #f8d7da; color: #721c24;">✗ Ditolak</span>
                    @endif
                </td>
                <td>
                    @if($loan->status == 'pending')
                        <form action="{{ route('admin.loans.approve', $loan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-approve" onclick="return confirm('Setujui peminjaman ini?')">Setujui</button>
                        </form>
                        <form action="{{ route('admin.loans.reject', $loan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-reject" onclick="return confirm('Tolak peminjaman ini?')">Tolak</button>
                        </form>
                    @elseif($loan->status == 'return_pending')
                        <form action="{{ route('admin.loans.approve-return', $loan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-approve" onclick="return confirm('Konfirmasi pengembalian ini?')">Konfirmasi Kembali</button>
                        </form>
                        <form action="{{ route('admin.loans.reject-return', $loan->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-action btn-reject" onclick="return confirm('Tolak pengembalian ini?')">Tolak</button>
                        </form>
                    @elseif($loan->status == 'approved')
                        <span style="color: #7f8c8d; font-size: 0.85rem;">Menunggu pengembalian</span>
                    @elseif($loan->status == 'returned')
                        <span style="color: #2ecc71; font-size: 0.85rem;">Selesai</span>
                    @else
                        <span style="color: #e74c3c; font-size: 0.85rem;">Ditolak</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #7f8c8d;">
                    Belum ada data peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const rows = document.querySelectorAll('#loansTableBody tr[data-status]');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const status = this.dataset.status;

            // Filter rows
            rows.forEach(row => {
                if (status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection
