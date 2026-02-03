<?php

namespace App\Http\Controllers;

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

    // Menampilkan Form Register (Penting untuk mengatasi error Anda!)
    public function showRegister() {
        return view('auth.register');
    }

    // Proses Registrasi (Sesuai alur Daftar Anggota di Flowmap)
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username, // Pastikan ini tidak null
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa', // Sesuai instruksi untuk pendaftaran anggota baru
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }

    public function login(Request $request)
{
    // 1. Validasi Input (Sesuai Flowmap: Input Username & Password)
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    // 2. Proses Otentikasi (Sesuai Flowmap: Validasi Login)
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Ambil data user yang login
        $user = Auth::user();

        // 3. Cek Role (Sesuai Flowmap: Cek Role Admin/Siswa)
        if ($user->role === 'admin') {
            return redirect()->intended('/kelola-buku'); // Ke halaman Admin
        }
        
        return redirect()->intended('/'); // Ke halaman Siswa (Dashboard)
    }

        // Jika Gagal (Sesuai Flowmap: Jika False -> Balik ke Login)
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