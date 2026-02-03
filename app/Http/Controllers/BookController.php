<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BookController extends Controller
{
    // Menampilkan halaman login sesuai desain tanpa navbar
    public function showLogin() { return view('auth.login'); }

    // Proses Validasi Login (Flowmap: Validasi Login)
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // Jika True -> Dashboard
        }

        return back()->withErrors(['username' => 'Username atau password salah.']); // Jika False -> Balik ke login
    }

    // Menampilkan halaman Daftar Anggota (Flowmap: Daftar Anggota)
    public function showRegister() { return view('auth.register'); }

    // Proses Registrasi Siswa Baru
    public function register(Request $request) {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'siswa', // Otomatis menjadi siswa 
        ]);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil, silakan login.');
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}
