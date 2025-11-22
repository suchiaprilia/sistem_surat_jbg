<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('jabatan', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required'
        ]);

        Jabatan::create($request->all());

        return redirect()->route('jabatan.index')
                         ->with('success', 'Jabatan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $editData = Jabatan::findOrFail($id);
        $jabatans = Jabatan::all();
        return view('jabatan', compact('jabatans', 'editData'));
    }

    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($request->all());

        return redirect()->route('jabatan.index')
                         ->with('success', 'Jabatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Jabatan::findOrFail($id)->delete();

        return redirect()->route('jabatan.index')
                         ->with('success', 'Jabatan berhasil dihapus!');
    }
}
