<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index()
    {
        // Mengambil user dengan role siswa agar tidak bercampur dengan admin
        $anggota = User::where('role', 'siswa')->get(); 
        return view('anggota.index', compact('anggota'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|min:8',
            'telepon'  => 'required',
            'alamat'   => 'required',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            // Mengatasi error Field 'username' doesn't have a default value
            'username' => explode('@', $request->email)[0], 
            'password' => Hash::make($request->password),
            'role'     => 'siswa', 
            'telepon'  => $request->telepon, // Memasukkan data telepon ke database
            'alamat'   => $request->alamat,  // Memasukkan data alamat ke database
        ]);

        return redirect()->back()->with('success', 'Akun siswa berhasil dibuat!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telepon' => 'required',
            'alamat' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => explode('@', $request->email)[0], // Update username jika email berubah
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
        ];

        // Hanya mengupdate password jika kolom password di form edit diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data siswa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'Akun siswa dihapus!');
    }
}