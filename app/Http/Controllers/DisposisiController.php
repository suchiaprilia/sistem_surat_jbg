<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    // tampilkan form disposisi
    public function create($suratMasukId)
    {
        $surat = SuratMasuk::findOrFail($suratMasukId);
        $karyawans = Karyawan::all();

        return view('disposisi.create', compact('surat', 'karyawans'));
    }

    // simpan disposisi
    public function store(Request $request)
    {
        $request->validate([
            'surat_masuk_id' => 'required|exists:surat_masuks,id',
            'ke_karyawan_id' => 'required|exists:karyawans,id_karyawan',
            'instruksi'      => 'required',
            'batas_waktu'    => 'nullable|date',
        ]);

        Disposisi::create([
            'surat_masuk_id'   => $request->surat_masuk_id,
            'dari_karyawan_id' => auth()->user()->karyawan_id ?? null,
            'ke_karyawan_id'   => $request->ke_karyawan_id,
            'instruksi'        => $request->instruksi,
            'batas_waktu'      => $request->batas_waktu,
            'status'           => 'baru',
        ]);

        return redirect()->back()->with('success', 'Disposisi berhasil dibuat');
    }
}
