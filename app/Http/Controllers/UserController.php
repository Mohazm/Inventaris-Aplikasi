<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user dengan role staff.
     */
    public function index(Request $request)
    {
        $roleFilter = $request->get('role', 'staff'); // Default filter role 'staff'

        $users = User::where('role', $roleFilter)->get();

        return view('Crud_admin.staff.index', compact('users', 'roleFilter'));
    }

    /**
     * Menampilkan form tambah user.
     */
    public function create()
    {
        return view('Crud_admin.staff.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        // Simpan data ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit user.
     */
    public function edit(User $user)
    {
        return view('Crud_admin.staff.edit', compact('user'));
    }

    /**
     * Mengupdate data user di database.
     */
    public function update(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Update data user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}
