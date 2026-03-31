<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    // User: Tampilkan form peminjaman
    public function create($bookId)
    {
        $book = Book::with('category')->findOrFail($bookId);
        
        // Cek ketersediaan buku
        if ($book->available <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }
        
        return view('user.loan-form', compact('book'));
    }
    
    // User: Submit pengajuan peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
        ]);
        
        $book = Book::findOrFail($request->book_id);
        
        // Cek lagi ketersediaan
        if ($book->available <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia.');
        }
        
        // Buat peminjaman
        $loan = Loan::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'loan_date' => $request->loan_date,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);
        
        return redirect()->route('user.loan.receipt', $loan->id)
            ->with('success', 'Pengajuan peminjaman berhasil dikirim!');
    }
    
    // User: Tampilkan bukti peminjaman
    public function receipt($id)
    {
        $loan = Loan::with(['book.category', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
            
        return view('user.loan-receipt', compact('loan'));
    }
    
    // User: Ajukan pengembalian
    public function returnRequest($id)
    {
        $loan = Loan::with(['book.category'])
            ->where('user_id', Auth::id())
            ->where('status', 'approved')
            ->findOrFail($id);
            
        return view('user.return-form', compact('loan'));
    }
    
    // User: Submit pengajuan pengembalian
    public function submitReturn($id)
    {
        $loan = Loan::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->findOrFail($id);
            
        $loan->update([
            'status' => 'return_pending',
            'return_date' => Carbon::now(),
        ]);
        
        return redirect()->route('user.loans')
            ->with('success', 'Pengajuan pengembalian berhasil dikirim!');
    }
    
    // Admin/Petugas: Tampilkan daftar peminjaman
    public function index()
    {
        $role = Auth::user()->role;
        $loans = Loan::with(['user', 'book', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        if ($role === 'admin') {
            return view('admin.loans', compact('loans'));
        } else {
            return view('petugas.loans', compact('loans'));
        }
    }
    
    // Admin/Petugas: Setujui peminjaman
    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $book = $loan->book;
        
        if ($book->available <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia.');
        }
        
        $loan->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);
        
        // Kurangi stok dan available buku
        $book->decrement('available');
        $book->decrement('stock');
        
        return redirect()->back()->with('success', 'Peminjaman disetujui!');
    }
    
    // Admin/Petugas: Tolak peminjaman
    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        
        $loan->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);
        
        return redirect()->back()->with('success', 'Peminjaman ditolak!');
    }
    
    // Admin/Petugas: Setujui pengembalian
    public function approveReturn($id)
    {
        $loan = Loan::findOrFail($id);
        $book = $loan->book;
        
        $loan->update([
            'status' => 'returned',
        ]);
        
        // Tambah stok dan available buku
        $book->increment('available');
        $book->increment('stock');
        
        return redirect()->back()->with('success', 'Pengembalian disetujui!');
    }
    
    // Admin/Petugas: Tolak pengembalian
    public function rejectReturn($id)
    {
        $loan = Loan::findOrFail($id);
        
        $loan->update([
            'status' => 'approved', // Kembali ke status dipinjam
        ]);
        
        return redirect()->back()->with('success', 'Pengembalian ditolak. User harus mengajukan kembali.');
    }
}
