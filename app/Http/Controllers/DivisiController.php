<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::all();
        return view('divisi', compact('divisis'));
    }

    public function create()
    {
        return view('divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required',
        ]);

        Divisi::create($request->all());

      return redirect()->route('divisi.index')
                ->with('success', 'Divisi berhasil ditambahkan!');

    }

    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('divisi.edit', compact('divisi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_divisi' => 'required',
        ]);

        Divisi::findOrFail($id)->update($request->all());

        return redirect()->route('divisi.index')
                         ->with('success', 'Divisi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Divisi::findOrFail($id)->delete();

        return redirect()->route('divisi.index')
                         ->with('success', 'Divisi berhasil dihapus!');
    }
}
