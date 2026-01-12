<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $suratKeluar = SuratKeluar::with('jenisSurat')->get(); // eager loading
        $jenisSurat = JenisSurat::all();
        return view('surat-keluar', compact('suratKeluar', 'jenisSurat'));
    }

    // FORM TAMBAH & EDIT SEKALIGUS
    public function create()
    {
        $suratKeluar = SuratKeluar::with('jenisSurat')->get();
        $jenisSurat = JenisSurat::all();
        return view('surat-keluar', compact('suratKeluar', 'jenisSurat'));
    }

    public function store(Request $request)
    {
        // Validasi semua field termasuk id_jenis_surat
        $request->validate([
            'no_surat_keluar' => 'required',
            'destination' => 'required',
            'subject' => 'required',
            'date' => 'required|date',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
        ]);

        SuratKeluar::create($request->all());
        return redirect()->route('surat-keluar.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $suratKeluar = SuratKeluar::with('jenisSurat')->get();
        $editData = SuratKeluar::findOrFail($id);
        $jenisSurat = JenisSurat::all();

        return view('surat-keluar', compact('suratKeluar', 'editData', 'jenisSurat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'no_surat_keluar' => 'required',
            'destination' => 'required',
            'subject' => 'required',
            'date' => 'required|date',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
        ]);

        $data = SuratKeluar::findOrFail($id);
        $data->update($request->all());

        return redirect()->route('surat-keluar.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        SuratKeluar::findOrFail($id)->delete();
        return redirect()->route('surat-keluar.index')->with('success', 'Data berhasil dihapus!');
    }
}
