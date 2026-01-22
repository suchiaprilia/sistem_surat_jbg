<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    /**
     * =========================
     * INBOX DISPOSISI (DEV MODE)
     * =========================
     * NOTE:
     * - Belum pakai login / auth
     * - karyawan_id masih MANUAL
     */
    public function index()
    {
        // 🔴 GANTI ANGKA INI SESUAI id_karyawan DI DATABASE
        $karyawanId = 1;

        $disposisis = Disposisi::with('suratMasuk')
            ->where('ke_karyawan_id', $karyawanId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('disposisi.index', compact('disposisis'));
    }

    /**
     * =========================
     * FORM KIRIM DISPOSISI
     * =========================
     */
    public function create($suratMasukId)
    {
        $surat = SuratMasuk::findOrFail($suratMasukId);
        $karyawans = Karyawan::all();

        return view('disposisi.create', compact('surat', 'karyawans'));
    }

    /**
     * =========================
     * SIMPAN DISPOSISI (DEV MODE)
     * =========================
     */
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
            'dari_karyawan_id' => 1, // 🔴 DEV MODE (sementara)
            'ke_karyawan_id'   => $request->ke_karyawan_id,
            'instruksi'        => $request->instruksi,
            'batas_waktu'      => $request->batas_waktu,
            'status'           => 'baru',
        ]);

        return redirect()->route('disposisi.index')
            ->with('success', 'Disposisi berhasil dikirim');
    }
}
