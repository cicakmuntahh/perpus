<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // User: Tampilkan katalog buku
    public function catalog()
    {
        $books = Book::with('category')->get();
        $categories = Category::all();
        
        // Jika user belum login, tampilkan katalog publik
        if (!auth()->check()) {
            return view('catalog-public', compact('books', 'categories'));
        }
        
        // Jika user sudah login, tampilkan katalog user
        return view('user.catalog', compact('books', 'categories'));
    }
    
    // User: Detail buku
    public function show($id)
    {
        $book = Book::with(['category', 'reviews.user'])->findOrFail($id);
        
        return view('user.book-detail', compact('book'));
    }
    
    // Admin/Petugas: Daftar buku
    public function index()
    {
        $books = Book::with('category')->get();
        $categories = Category::all();
        $role = auth()->user()->role;
        
        if ($role === 'admin') {
            return view('admin.books', compact('books', 'categories'));
        } else {
            return view('petugas.books', compact('books', 'categories'));
        }
    }
    
    // Admin/Petugas: Simpan buku baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'required|string|unique:books,isbn',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'pages' => 'required|integer|min:1',
            'stock' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);
        
        $coverPath = 'https://via.placeholder.com/300x400?text=No+Cover';
        
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/covers'), $filename);
            $coverPath = '/uploads/covers/' . $filename;
        }
        
        Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'category_id' => $request->category_id,
            'isbn' => $request->isbn,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'pages' => $request->pages,
            'stock' => $request->stock,
            'available' => $request->stock,
            'cover_image' => $coverPath,
            'rating' => 0,
        ]);
        
        $role = auth()->user()->role;
        return redirect()->route($role . '.books')->with('success', 'Buku berhasil ditambahkan!');
    }
    
    // Admin/Petugas: Update buku
    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'isbn' => 'required|string|unique:books,isbn,' . $id,
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'pages' => 'required|integer|min:1',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);
        
        // Hitung selisih stock untuk update available
        $stockDiff = $request->stock - $book->stock;
        $newAvailable = $book->available + $stockDiff;
        
        // Pastikan available tidak negatif
        if ($newAvailable < 0) {
            $newAvailable = 0;
        }
        
        $coverPath = $book->cover_image;
        
        if ($request->hasFile('cover_image')) {
            // Hapus cover lama jika bukan placeholder dan bukan URL eksternal
            if ($book->cover_image && !str_contains($book->cover_image, 'placeholder') && !str_contains($book->cover_image, 'http')) {
                $oldPath = public_path($book->cover_image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/covers'), $filename);
            $coverPath = '/uploads/covers/' . $filename;
        }
        
        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'category_id' => $request->category_id,
            'isbn' => $request->isbn,
            'publisher' => $request->publisher,
            'year' => $request->year,
            'pages' => $request->pages,
            'stock' => $request->stock,
            'available' => $newAvailable,
            'cover_image' => $coverPath,
        ]);
        
        $role = auth()->user()->role;
        return redirect()->route($role . '.books')->with('success', 'Buku berhasil diupdate!');
    }
    
    // Admin/Petugas: Hapus buku
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        
        // Cek apakah buku sedang dipinjam
        $activeLoan = $book->loans()->whereIn('status', ['pending', 'approved', 'return_pending'])->count();
        
        if ($activeLoan > 0) {
            $role = auth()->user()->role;
            return redirect()->route($role . '.books')->with('error', 'Buku tidak bisa dihapus karena sedang dipinjam!');
        }
        
        $book->delete();
        
        $role = auth()->user()->role;
        return redirect()->route($role . '.books')->with('success', 'Buku berhasil dihapus!');
    }
}
