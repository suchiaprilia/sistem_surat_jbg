<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    // Menampilkan semua data surat masuk
    public function index()
    {
        $suratMasuk = SuratMasuk::all();
        return view('surat-masuk', compact('suratMasuk'));
    }

    // Menampilkan form tambah data
    public function create()
    {
        return view('surat-masuk.create');
    }

    // Menyimpan data baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
            'pengirim' => 'required',
            'perihal' => 'required',
        ]);

        SuratMasuk::create($request->all());
        return redirect()->route('surat-masuk')->with('success', 'Data berhasil ditambahkan!');
    }

    // Menampilkan form edit data
    public function edit($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);
        return view('surat-masuk.edit', compact('suratMasuk'));
    }

    // Memperbarui data di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'nomor_surat' => 'required',
            'tanggal_surat' => 'required|date',
            'pengirim' => 'required',
            'perihal' => 'required',
        ]);

        $suratMasuk = SuratMasuk::findOrFail($id);
        $suratMasuk->update($request->all());

        return redirect()->route('surat-masuk')->with('success', 'Data berhasil diupdate!');
    }

    // Menghapus data dari database
    public function destroy($id)
    {
        $suratMasuk = SuratMasuk::findOrFail($id);
        $suratMasuk->delete();

        return redirect()->route('surat-masuk')->with('success', 'Data berhasil dihapus!');
    }
}
