<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisSurats = JenisSurat::orderBy('created_at', 'desc')->get();
        return view('jenis-surat', compact('jenisSurats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
        ]);

        JenisSurat::create($request->only('jenis_surat'));

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis surat berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
        ]);

        JenisSurat::findOrFail($id)->update($request->only('jenis_surat'));

        return redirect()->route('jenis-surat.index')->with('success', 'Jenis surat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        JenisSurat::findOrFail($id)->delete();
        return redirect()->route('jenis-surat.index')->with('success', 'Jenis surat berhasil dihapus!');
    }

    // Redirect untuk aksi yang tidak digunakan
    public function create() { return redirect()->route('jenis-surat.index'); }
    public function edit($id) { return redirect()->route('jenis-surat.index'); }
    public function show($id) { return redirect()->route('jenis-surat.index'); }
}
