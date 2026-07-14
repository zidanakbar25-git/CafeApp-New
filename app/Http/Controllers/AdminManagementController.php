<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminManagementController extends Controller
{
    // ── INDEX ──────────────────────────────────────────
    public function index()
    {
        $admins = Admin::where('role', 'cashier')
            ->orderBy('admin_id')
            ->get();
        return view('admin.manager.management.admins.index', compact('admins'));
    }

    // ── CREATE ─────────────────────────────────────────
    public function create()
    {
        $this->authorizeSuper();
        return view('admin.manager.management.admins.create');
    }

    // ── STORE ──────────────────────────────────────────
    public function store(Request $request)
    {
        $this->authorizeSuper();

        $request->validate([
            'username' => 'required|string|max:100|unique:admins,username',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => 'cashier',
        ]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Akun kasir berhasil ditambahkan.');
    }

    // ── EDIT ───────────────────────────────────────────
    public function edit($id)
    {
        $this->authorizeSuper();
        $admin = Admin::findOrFail($id);
        return view('admin.manager.management.admins.edit', compact('admin'));
    }

    // ── UPDATE ─────────────────────────────────────────
    public function update(Request $request, $id)
    {
        $this->authorizeSuper();
        $admin = Admin::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:100|unique:admins,username,' . $id . ',admin_id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = ['username' => $request->username];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Akun kasir berhasil diperbarui.');
    }

    // ── DESTROY ────────────────────────────────────────
    public function destroy($id)
    {
        $this->authorizeSuper();
        $admin = Admin::findOrFail($id);

        if (auth()->user()->admin_id == $id) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang login.');
        }

        $admin->delete();
        return back()->with('success', 'Admin berhasil dihapus.');
    }

    // ── RESET PASSWORD ─────────────────────────────────
    public function resetPassword($id)
    {
        $this->authorizeSuper();
        $admin = Admin::findOrFail($id);

        $newPassword = Str::random(8);
        $admin->update(['password' => Hash::make($newPassword)]);

        return back()->with([
            'reset_success' => true,
            'reset_name'    => $admin->name ?? $admin->username,
            'new_password'  => $newPassword,
        ]);
    }

    // ── HELPER ─────────────────────────────────────────
    private function authorizeSuper()
    {
        if (auth()->user()->role !== 'manager') {
            abort(403);
        }
    }
}