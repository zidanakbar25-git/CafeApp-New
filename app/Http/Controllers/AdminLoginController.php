<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminLoginController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();

        // ✔ koreksi: validasi aman
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return back()->with('error', 'Username atau password salah');
        }

        Auth::login($admin);

        // ✔ koreksi: 1 dashboard saja
        return redirect('/dashboard');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}