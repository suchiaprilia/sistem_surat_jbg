<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with(['divisi', 'jabatan'])->get();
        $divisi = Divisi::all();
        $jabatan = Jabatan::all();

        return view('karyawan', compact('karyawans', 'divisi', 'jabatan'));
    }

public function store(Request $request)
{
    // ✅ biar hanya admin yang bisa tambah
    if (session('role') !== 'admin') {
        abort(403);
    }

    $request->validate([
        'nama_karyawan' => 'required|regex:/^[A-Za-z\s]+$/',
        'email_karyawan' => 'required|email|unique:users,email',
        'id_divisi' => 'required',
        'id_jabatan' => 'required',
    ], [
        'nama_karyawan.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
    ]);

    DB::transaction(function () use ($request) {

    // 1️⃣ buat / ambil akun user
    $user = User::firstOrCreate(
        ['email' => $request->email_karyawan],
        [
            'nama' => $request->nama_karyawan,
            'jabatan' => 'karyawan',
            'role' => 'user',
            'password' => md5('123456'),
        ]
    );

    // 🔎 DEBUG AMAN (opsional)
    if (!$user->id_user) {
        throw new \Exception('User ID tidak terbentuk');
    }

    // 2️⃣ simpan karyawan + relasi ke user
    Karyawan::create([
        'user_id' => $user->id_user,   // 🔑 INI KUNCI
        'nama_karyawan' => $request->nama_karyawan,
        'email_karyawan' => $request->email_karyawan,
        'id_divisi' => $request->id_divisi,
        'id_jabatan' => $request->id_jabatan,
        'role' => 'user',
    ]);
});



    return redirect()->route('karyawan.index')
        ->with('success', 'Karyawan berhasil ditambahkan! Akun login dibuat (password default: 123456).');
}


    public function edit($id)
    {
        $editData = Karyawan::findOrFail($id);
        $karyawans = Karyawan::with(['divisi', 'jabatan'])->get();
        $divisi = Divisi::all();
        $jabatan = Jabatan::all();

        return view('karyawan', compact(
            'karyawans', 'editData', 'divisi', 'jabatan'
        ));
    }

   public function update(Request $request, $id)
{
    if (session('role') !== 'admin') {
        abort(403);
    }

    $request->validate([
        'nama_karyawan' => 'required|regex:/^[A-Za-z\s]+$/',
        'email_karyawan' => 'required|email|unique:karyawans,email_karyawan,' . $id . ',id_karyawan',
        'id_divisi' => 'required',
        'id_jabatan' => 'required',
    ], [
        'nama_karyawan.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
    ]);

    $karyawan = Karyawan::findOrFail($id);

    // simpan email lama untuk cari akun user
    $emailLama = $karyawan->email_karyawan;

    DB::transaction(function () use ($request, $karyawan, $emailLama) {

        // 1) update data karyawan
        $karyawan->update([
            'nama_karyawan' => $request->nama_karyawan,
            'email_karyawan' => $request->email_karyawan,
            'id_divisi' => $request->id_divisi,
            'id_jabatan' => $request->id_jabatan,
        ]);

        // 2) update akun login di tabel user (berdasarkan email lama)
        User::where('email', $emailLama)->update([
            'nama' => $request->nama_karyawan,
            'email' => $request->email_karyawan,
        ]);
    });

    return redirect()->route('karyawan.index')
        ->with('success', 'Karyawan + akun login berhasil diperbarui!');
}


    public function destroy($id)
{
    if (session('role') !== 'admin') {
        abort(403);
    }

    $karyawan = Karyawan::findOrFail($id);

    DB::transaction(function () use ($karyawan) {
        // hapus akun login berdasarkan email yang sama
        User::where('email', $karyawan->email_karyawan)->delete();

        // hapus data karyawan
        $karyawan->delete();
    });

    return redirect()->route('karyawan.index')
        ->with('success', 'Karyawan + akun login berhasil dihapus!');
}
public function resetPassword($id)
{
    if (session('role') !== 'admin') {
        abort(403);
    }

    $karyawan = Karyawan::findOrFail($id);

    // reset password akun login berdasarkan email yang sama
    User::where('email', $karyawan->email_karyawan)->update([
        'password' => md5('123456')
    ]);

    return redirect()->route('karyawan.index')
        ->with('success', 'Password akun login berhasil direset! (Password: 123456)');
}

}
