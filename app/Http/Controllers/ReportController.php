<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Admin/Petugas: Halaman laporan
    public function index()
    {
        $role = auth()->user()->role;
        
        if ($role === 'admin') {
            return view('admin.reports');
        } else {
            return view('petugas.reports');
        }
    }
    
    // Laporan Data Buku
    public function books()
    {
        $books = Book::with('category')->get();
        $role = auth()->user()->role;
        
        return view('reports.books', compact('books', 'role'));
    }
    
    // Laporan Peminjaman
    public function loans()
    {
        $loans = Loan::with(['user', 'book'])
            ->whereIn('status', ['pending', 'approved', 'return_pending'])
            ->orderBy('created_at', 'desc')
            ->get();
        $role = auth()->user()->role;
        
        return view('reports.loans', compact('loans', 'role'));
    }
    
    // Laporan Pengembalian
    public function returns()
    {
        $returns = Loan::with(['user', 'book'])
            ->where('status', 'returned')
            ->orderBy('return_date', 'desc')
            ->get();
        $role = auth()->user()->role;
        
        return view('reports.returns', compact('returns', 'role'));
    }
    
    // Laporan Data User
    public function users()
    {
        $users = User::where('role', 'user')->get();
        $role = auth()->user()->role;
        
        return view('reports.users', compact('users', 'role'));
    }
}
