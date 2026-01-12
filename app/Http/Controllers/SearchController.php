<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return redirect()->back();
        }

        $suratMasuk = SuratMasuk::where('no_surat', 'like', "%{$query}%")
            ->orWhere('subject', 'like', "%{$query}%")
            ->orWhere('pengirim', 'like', "%{$query}%")
            ->get();

        $suratKeluar = SuratKeluar::where('no_surat_keluar', 'like', "%{$query}%")
            ->orWhere('subject', 'like', "%{$query}%")
            ->orWhere('destination', 'like', "%{$query}%")
            ->get();

        return view('search-results', compact('query', 'suratMasuk', 'suratKeluar'));
    }
}
