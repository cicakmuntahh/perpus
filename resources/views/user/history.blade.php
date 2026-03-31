@extends('layouts.user')

@section('title', 'Riwayat Peminjaman')
@section('page-title', 'Riwayat Peminjaman')
@section('page-subtitle', 'Lihat riwayat peminjaman buku Anda')

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

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-returned {
        background: #d4edda;
        color: #155724;
    }

    .status-late {
        background: #f8d7da;
        color: #721c24;
    }

    .btn-review {
        padding: 0.5rem 1rem;
        background: #2ecc71;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .btn-review:hover {
        background: #27ae60;
    }

    .btn-review:disabled {
        background: #95a5a6;
        cursor: not-allowed;
    }

    .review-display {
        font-size: 0.85rem;
        color: #7f8c8d;
    }

    .rating-stars {
        color: #f39c12;
    }
</style>

<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Denda</th>
                <th>Ulasan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
            @php
                $dueDate = \Carbon\Carbon::parse($loan->due_date);
                $returnDate = \Carbon\Carbon::parse($loan->return_date);
                $daysLate = $returnDate->gt($dueDate) ? $returnDate->diffInDays($dueDate) : 0;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $loan->book->title }}</td>
                <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                <td>{{ $returnDate->format('d M Y') }}</td>
                <td>
                    @if($loan->fine > 0)
                        <span class="status-badge status-late">Rp {{ number_format($loan->fine, 0, ',', '.') }}</span>
                    @else
                        <span style="color: #2ecc71;">Tepat Waktu</span>
                    @endif
                </td>
                <td>
                    @if($loan->review)
                        <div class="review-display">
                            <div class="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $loan->review->rating)
                                        ⭐
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                            <div>{{ Str::limit($loan->review->comment, 50) }}</div>
                        </div>
                    @else
                        <span style="color: #95a5a6;">Belum diulas</span>
                    @endif
                </td>
                <td>
                    @if($loan->review)
                        <button class="btn-review" disabled>✓ Sudah Diulas</button>
                    @else
                        <button class="btn-review" onclick="openReviewModal({{ $loan->id }}, '{{ $loan->book->title }}')">✍️ Tulis Ulasan</button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #7f8c8d;">
                    Belum ada riwayat peminjaman
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Review -->
<div id="reviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="max-width: 500px; margin: 100px auto; background: white; border-radius: 15px; padding: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Tulis Ulasan</h2>
        <form action="{{ route('user.review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="loan_id" id="review_loan_id">
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Buku</label>
                <div id="review_book_title" style="padding: 0.8rem; background: #f8f9fa; border-radius: 8px; color: #2c3e50;"></div>
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Rating *</label>
                <div style="display: flex; gap: 0.5rem; font-size: 2rem;">
                    <span class="star" data-rating="1" onclick="setRating(1)" style="cursor: pointer; color: #ddd;">★</span>
                    <span class="star" data-rating="2" onclick="setRating(2)" style="cursor: pointer; color: #ddd;">★</span>
                    <span class="star" data-rating="3" onclick="setRating(3)" style="cursor: pointer; color: #ddd;">★</span>
                    <span class="star" data-rating="4" onclick="setRating(4)" style="cursor: pointer; color: #ddd;">★</span>
                    <span class="star" data-rating="5" onclick="setRating(5)" style="cursor: pointer; color: #ddd;">★</span>
                </div>
                <input type="hidden" name="rating" id="rating_value" required>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Komentar (Opsional)</label>
                <textarea name="comment" rows="4" placeholder="Bagikan pengalaman Anda tentang buku ini..." style="width: 100%; padding: 0.8rem; border: 2px solid #ecf0f1; border-radius: 8px; resize: vertical;"></textarea>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeReviewModal()" style="flex: 1; padding: 0.8rem; background: #ecf0f1; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Batal</button>
                <button type="submit" style="flex: 1; padding: 0.8rem; background: #2ecc71; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedRating = 0;

function openReviewModal(loanId, bookTitle) {
    document.getElementById('review_loan_id').value = loanId;
    document.getElementById('review_book_title').textContent = bookTitle;
    document.getElementById('reviewModal').style.display = 'block';
    selectedRating = 0;
    document.getElementById('rating_value').value = '';
    resetStars();
}

function closeReviewModal() {
    document.getElementById('reviewModal').style.display = 'none';
}

function setRating(rating) {
    selectedRating = rating;
    document.getElementById('rating_value').value = rating;
    
    const stars = document.querySelectorAll('.star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.style.color = '#f39c12';
        } else {
            star.style.color = '#ddd';
        }
    });
}

function resetStars() {
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.style.color = '#ddd';
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('reviewModal');
    if (event.target == modal) {
        closeReviewModal();
    }
}
</script>
@endsection
