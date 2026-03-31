<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Admin/Petugas: Daftar kategori
    public function index()
    {
        $categories = Category::withCount('books')->get();
        $role = auth()->user()->role;
        
        if ($role === 'admin') {
            return view('admin.categories', compact('categories'));
        } else {
            return view('petugas.categories', compact('categories'));
        }
    }
    
    // Admin: Simpan kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);
        
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        
        $role = auth()->user()->role;
        return redirect()->route($role . '.categories')->with('success', 'Kategori berhasil ditambahkan!');
    }
    
    // Admin: Update kategori
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);
        
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        
        $role = auth()->user()->role;
        return redirect()->route($role . '.categories')->with('success', 'Kategori berhasil diupdate!');
    }
    
    // Admin: Hapus kategori
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Cek apakah kategori memiliki buku
        if ($category->books()->count() > 0) {
            $role = auth()->user()->role;
            return redirect()->route($role . '.categories')->with('error', 'Kategori tidak bisa dihapus karena masih memiliki buku!');
        }
        
        $category->delete();
        
        $role = auth()->user()->role;
        return redirect()->route($role . '.categories')->with('success', 'Kategori berhasil dihapus!');
    }
}
