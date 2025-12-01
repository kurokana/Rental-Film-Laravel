<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserRoleController extends Controller
{
    // Daftar users
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('owner.users.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        return view('owner.users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,pegawai,owner',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Audit log
        AuditLog::log(
            'create',
            'User',
            $user->id,
            "User {$user->name} ({$user->role}) ditambahkan oleh " . auth()->user()->name,
            null,
            $user->makeVisible('password')->toArray()
        );

        return redirect()->route('owner.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    // Form edit user
    public function edit(User $user)
    {
        return view('owner.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:user,pegawai,owner',
            'phone' => 'required|string|max:15',
            'address' => 'required|string',
        ]);

        $oldData = $user->toArray();

        $data = $request->except('password', 'password_confirmation');
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Audit log
        AuditLog::log(
            'update',
            'User',
            $user->id,
            "User {$user->name} ({$user->role}) diupdate oleh " . auth()->user()->name,
            $oldData,
            $user->toArray()
        );

        return redirect()->route('owner.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    // Hapus user
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Check if user has active rentals
        if ($user->rentals()->whereIn('status', ['pending', 'active', 'extended', 'overdue'])->exists()) {
            return back()->with('error', 'User tidak dapat dihapus karena masih memiliki rental aktif.');
        }

        $oldData = $user->toArray();
        $user->delete();

        // Audit log
        AuditLog::log(
            'delete',
            'User',
            $user->id,
            "User {$user->name} ({$user->role}) dihapus oleh " . auth()->user()->name,
            $oldData,
            null
        );

        return redirect()->route('owner.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    // Change user role
    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,pegawai,owner',
        ]);

        // Prevent changing own role
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role Anda sendiri.');
        }

        $oldRole = $user->role;
        $user->update(['role' => $request->role]);

        // Audit log
        AuditLog::log(
            'update',
            'User',
            $user->id,
            "Role user {$user->name} diubah dari {$oldRole} menjadi {$request->role} oleh " . auth()->user()->name,
            ['role' => $oldRole],
            ['role' => $request->role]
        );

        return back()->with('success', 'Role user berhasil diubah.');
    }
}
