<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::all();
        return view('surat-masuk', compact('suratMasuk'));
    }

    public function create()
    {
        return view('surat-masuk-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required',
            'pengirim' => 'required',
            'subject' => 'required',
            'tujuan' => 'required',
            'file_surat' => 'nullable|mimes:pdf,jpg,png'
        ]);

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')
                ->store('surat-masuk', 'public');
        }

        SuratMasuk::create($validated);

        return redirect()->route('surat-masuk.index');
    }

    public function edit($id)
    {
        $item = SuratMasuk::findOrFail($id);
        return view('surat-masuk-edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required',
            'pengirim' => 'required',
            'subject' => 'required',
            'tujuan' => 'required',
            'file_surat' => 'nullable|mimes:pdf,jpg,png'
        ]);

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request->file('file_surat')
                ->store('surat-masuk', 'public');
        }

        $item->update($validated);

        return redirect()->route('surat-masuk.index');
    }

    public function destroy($id)
    {
        $item = SuratMasuk::findOrFail($id);
        $item->delete();
        return redirect()->route('surat-masuk.index');
    }
}

