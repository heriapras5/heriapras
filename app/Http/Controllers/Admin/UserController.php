<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $users = User::when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"))
            ->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form', ['user' => new User]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
            'role' => 'required|in:admin,super_admin',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = true;
        $data['role'] = $data['role'] === 'super_admin' ? 'super_admin' : 'admin';
        User::create($data);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,super_admin',
        ]);
        // Proteksi: super admin utama (id 1) tidak bisa di-demote, diri sendiri tidak bisa di-demote.
        if ($user->id === 1 || $user->id === auth()->id()) {
            $data['role'] = $user->role ?: 'super_admin';
            $data['is_admin'] = true;
        } else {
            $data['is_admin'] = true;
            $data['role'] = $data['role'] === 'super_admin' ? 'super_admin' : 'admin';
        }
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === 1) {
            return back()->with('error', 'Super admin utama tidak dapat dihapus.');
        }
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        if ($user->isSuperAdmin() && User::where('role', 'super_admin')->where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus satu-satunya Super Admin.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
