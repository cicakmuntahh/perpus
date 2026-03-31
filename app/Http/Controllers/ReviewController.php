<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Admin/Petugas: Lihat semua ulasan
    public function index()
    {
        $reviews = Review::with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $role = auth()->user()->role;
        
        if ($role === 'admin') {
            return view('admin.reviews', compact('reviews'));
        } else {
            return view('petugas.reviews', compact('reviews'));
        }
    }
    
    // User: Simpan ulasan
    public function store(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);
        
        $loan = Loan::findOrFail($request->loan_id);
        
        // Cek apakah user adalah pemilik loan
        if ($loan->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk memberikan ulasan ini!');
        }
        
        // Cek apakah buku sudah dikembalikan
        if ($loan->status !== 'returned') {
            return redirect()->back()->with('error', 'Anda hanya bisa memberikan ulasan setelah mengembalikan buku!');
        }
        
        // Cek apakah sudah ada review
        $existingReview = Review::where('loan_id', $loan->id)->first();
        if ($existingReview) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk buku ini!');
        }
        
        // Buat review
        Review::create([
            'user_id' => auth()->id(),
            'book_id' => $loan->book_id,
            'loan_id' => $loan->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
        
        // Update rating buku
        $book = Book::findOrFail($loan->book_id);
        $avgRating = Review::where('book_id', $book->id)->avg('rating');
        $book->update(['rating' => $avgRating]);
        
        return redirect()->route('user.history')->with('success', 'Ulasan berhasil ditambahkan!');
    }
    
    // Admin/Petugas: Hapus ulasan
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $bookId = $review->book_id;
        
        $review->delete();
        
        // Update rating buku setelah ulasan dihapus
        $book = Book::findOrFail($bookId);
        $avgRating = Review::where('book_id', $bookId)->avg('rating');
        $book->update(['rating' => $avgRating ?? 0]);
        
        $role = auth()->user()->role;
        return redirect()->route($role . '.reviews')->with('success', 'Ulasan berhasil dihapus!');
    }
}
