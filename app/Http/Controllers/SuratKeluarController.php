<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $suratKeluar = SuratKeluar::all();
        return view('surat-keluar', compact('suratKeluar'));
    }

    // FORM TAMBAH & EDIT SEKALIGUS
    public function create()
    {
        $suratKeluar = SuratKeluar::all();
        return view('surat-keluar', compact('suratKeluar'));
    }

    public function store(Request $request)
    {
        SuratKeluar::create($request->all());
        return redirect()->route('surat-keluar.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $suratKeluar = SuratKeluar::all();
        $editData = SuratKeluar::findOrFail($id);

        return view('surat-keluar', compact('suratKeluar', 'editData'));
    }

    public function update(Request $request, $id)
    {
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
