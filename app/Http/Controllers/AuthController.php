<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan Form Login
    public function showLogin() {
        return view('auth.login');
    }

    // Menampilkan Form Register
    public function showRegister() {
        return view('auth.register');
    }

    // Proses Registrasi
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa', 
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }

    public function login(Request $request)
{
    // Validasi Input
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // Proses Validasi Login
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Ambil data user yang login
        $user = Auth::user();

        // Cek role (Admin/Siswa)
        if ($user->role === 'admin') {
            return redirect()->intended('/kelola-buku'); 
        }
        
        return redirect()->intended('/'); 
    }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}