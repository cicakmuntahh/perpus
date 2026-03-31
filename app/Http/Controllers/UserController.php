<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Admin: Daftar user
    public function indexUsers()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.users', compact('users'));
    }
    
    // Admin: Daftar petugas
    public function indexOfficers()
    {
        $officers = User::where('role', 'petugas')->get();
        return view('admin.officers', compact('officers'));
    }
    
    // Admin: Tambah petugas
    public function storeOfficer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
        ]);
        
        return redirect()->route('admin.officers')->with('success', 'Petugas berhasil ditambahkan!');
    }
    
    // Admin: Update petugas
    public function updateOfficer(Request $request, $id)
    {
        $officer = User::where('role', 'petugas')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        
        $officer->update($data);
        
        return redirect()->route('admin.officers')->with('success', 'Petugas berhasil diupdate!');
    }
    
    // Admin: Hapus petugas
    public function destroyOfficer($id)
    {
        $officer = User::where('role', 'petugas')->findOrFail($id);
        $officer->delete();
        
        return redirect()->route('admin.officers')->with('success', 'Petugas berhasil dihapus!');
    }
    
    // Admin: Hapus user
    public function destroyUser($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        
        // Cek apakah user memiliki peminjaman aktif
        $activeLoans = $user->loans()->whereIn('status', ['pending', 'approved', 'return_pending'])->count();
        
        if ($activeLoans > 0) {
            return redirect()->route('admin.users')->with('error', 'User tidak bisa dihapus karena masih memiliki peminjaman aktif!');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus!');
    }
}
