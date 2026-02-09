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
        'nama_karyawan'  => 'required|regex:/^[A-Za-z\s]+$/',
        'email_karyawan' => 'required|email|unique:user,email', // ✅ tabel kamu "user"
        'id_divisi'      => 'required',
        'id_jabatan'     => 'required',
        'role'           => 'required|in:admin,pimpinan,staff', // ✅ tambah role
    ], [
        'nama_karyawan.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
    ]);

    DB::transaction(function () use ($request) {

        // ambil nama jabatan dari tabel jabatans
        $jabatan = Jabatan::where('id_jabatan', $request->id_jabatan)->firstOrFail();

        // 1️⃣ buat / ambil akun user
        $user = User::firstOrCreate(
            ['email' => $request->email_karyawan],
            [
                'nama'     => $request->nama_karyawan,
                'jabatan'  => $jabatan->nama_jabatan, // ✅ isi sesuai pilihan jabatan
                'role'     => $request->role,         // ✅ role dari form
                'password' => md5('123456'),
            ]
        );

        // kalau user sudah ada (firstOrCreate menemukan existing), update role+jabatan biar sinkron
        $user->update([
            'nama'    => $request->nama_karyawan,
            'jabatan' => $jabatan->nama_jabatan,
            'role'    => $request->role,
        ]);

        // 2️⃣ simpan karyawan + relasi ke user
        Karyawan::create([
            'user_id'        => $user->id_user,
            'nama_karyawan'  => $request->nama_karyawan,
            'email_karyawan' => $request->email_karyawan,
            'id_divisi'      => $request->id_divisi,
            'id_jabatan'     => $request->id_jabatan,

            // ✅ tetap isi juga untuk kompatibilitas kode lama
            'role'           => $request->role,
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
        'nama_karyawan'  => 'required|regex:/^[A-Za-z\s]+$/',
        'email_karyawan' => 'required|email|unique:karyawans,email_karyawan,' . $id . ',id_karyawan',
        'id_divisi'      => 'required',
        'id_jabatan'     => 'required',
        'role'           => 'required|in:admin,pimpinan,staff', // ✅ TAMBAH
    ], [
        'nama_karyawan.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
    ]);

    $karyawan = Karyawan::findOrFail($id);

    // simpan email lama untuk cari akun user
    $emailLama = $karyawan->email_karyawan;

    DB::transaction(function () use ($request, $karyawan, $emailLama) {

        // ✅ ambil nama jabatan dari tabel jabatans
        $jabatan = Jabatan::where('id_jabatan', $request->id_jabatan)->first();

        // 1) update data karyawan
        $karyawan->update([
            'nama_karyawan'  => $request->nama_karyawan,
            'email_karyawan' => $request->email_karyawan,
            'id_divisi'      => $request->id_divisi,
            'id_jabatan'     => $request->id_jabatan,
            'role'           => $request->role, // ✅ TAMBAH (biar kompatibel kode lama)
        ]);

        // 2) update akun login di tabel user (berdasarkan email lama)
        User::where('email', $emailLama)->update([
            'nama'    => $request->nama_karyawan,
            'email'   => $request->email_karyawan,
            'role'    => $request->role, // ✅ TAMBAH (hak akses)
            'jabatan' => $jabatan ? $jabatan->nama_jabatan : null, // ✅ sinkron jabatan
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
