<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\JenisSurat;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::with('jenisSurat')->get(); // eager loading
        $jenisSurat = JenisSurat::all();
        return view('surat-masuk', compact('suratMasuk', 'jenisSurat'));
    }

    public function create()
    {
        return view('surat-masuk-create');
    }

    public function store(Request $request)
    {
        // ✅ TAMBAHKAN VALIDASI id_jenis_surat
        $validated = $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required',
            'pengirim' => 'required',
            'subject' => 'required',
            'tujuan' => 'required',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat', // ← INI YANG KURANG!
            'file_surat' => 'nullable|mimes:pdf,jpg,png'
        ]);

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')
                ->store('surat-masuk', 'public');
        }

        SuratMasuk::create($validated);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = SuratMasuk::findOrFail($id);
        $jenisSurat = JenisSurat::all(); // kirim data jenis surat untuk dropdown
        return view('surat-masuk-edit', compact('item', 'jenisSurat'));
    }

    public function update(Request $request, $id)
    {
        $item = SuratMasuk::findOrFail($id);

        // ✅ TAMBAHKAN VALIDASI id_jenis_surat
        $validated = $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required',
            'pengirim' => 'required',
            'subject' => 'required',
            'tujuan' => 'required',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat', // ← INI YANG KURANG!
            'file_surat' => 'nullable|mimes:pdf,jpg,png'
        ]);

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')
                ->store('surat-masuk', 'public');
        }

        $item->update($validated);

        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = SuratMasuk::findOrFail($id);
        $item->delete();
        return redirect()->route('surat-masuk.index')->with('success', 'Surat masuk berhasil dihapus.');
    }
}
