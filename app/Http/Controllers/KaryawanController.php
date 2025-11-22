<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;

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
        $request->validate([
            'nama_karyawan' => 'required',
            'email_karyawan' => 'required|email|unique:karyawans,email_karyawan',
            'id_divisi' => 'required',
            'id_jabatan' => 'required',
        ]);

        Karyawan::create($request->all());

        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil ditambahkan!');
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
        $request->validate([
            'nama_karyawan' => 'required',
            'email_karyawan' => 'required|email',
            'id_divisi' => 'required',
            'id_jabatan' => 'required',
        ]);

        $karyawan = Karyawan::findOrFail($id);
        $karyawan->update($request->all());

        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Karyawan::destroy($id);

        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil dihapus!');
    }
}
