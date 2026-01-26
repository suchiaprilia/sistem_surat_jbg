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
     */
    public function index()
    {
        // DEV MODE: ganti sesuai id_karyawan
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
     * SIMPAN DISPOSISI
     * (NORMAL & BERANTAI)
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

    // 🔁 JIKA DISPOSISI TERUSAN
    if ($request->filled('disposisi_lama_id')) {
        $disposisiLama = Disposisi::findOrFail($request->disposisi_lama_id);

        // tandai disposisi lama selesai
        $disposisiLama->update([
            'status' => 'selesai'
        ]);

        // buat disposisi baru (BERANTAI)
        Disposisi::create([
            'parent_id'        => $disposisiLama->id,      // 🔥 RIWAYAT
            'surat_masuk_id'   => $disposisiLama->surat_masuk_id,
            'dari_karyawan_id' => $disposisiLama->ke_karyawan_id,
            'ke_karyawan_id'   => $request->ke_karyawan_id,
            'instruksi'        => $request->instruksi,
            'batas_waktu'      => $request->batas_waktu,
            'status'           => 'baru',
        ]);

    } else {
        // DISPOSISI PERTAMA KALI
        Disposisi::create([
            'surat_masuk_id'   => $request->surat_masuk_id,
            'dari_karyawan_id' => 1, // DEV MODE
            'ke_karyawan_id'   => $request->ke_karyawan_id,
            'instruksi'        => $request->instruksi,
            'batas_waktu'      => $request->batas_waktu,
            'status'           => 'baru',
        ]);
    }

    return redirect()->route('disposisi.index')
        ->with('success', 'Disposisi berhasil dikirim');
}


    /**
     * =========================
     * UPDATE STATUS: DIBACA
     * =========================
     */
    public function markRead($id)
    {
        Disposisi::where('id', $id)
            ->update(['status' => 'dibaca']);

        return redirect()->back()
            ->with('success', 'Disposisi ditandai sebagai dibaca');
    }

    /**
     * =========================
     * UPDATE STATUS: SELESAI
     * =========================
     */
    public function markDone($id)
    {
        Disposisi::where('id', $id)
            ->update(['status' => 'selesai']);

        return redirect()->back()
            ->with('success', 'Disposisi telah diselesaikan');
    }

    /**
     * =========================
     * FORM TERUSKAN DISPOSISI
     * =========================
     */
    public function forward($id)
    {
        $disposisi = Disposisi::with('suratMasuk')->findOrFail($id);
        $karyawans = Karyawan::all();

        return view('disposisi.forward', compact('disposisi', 'karyawans'));
    }

        /**
     * =========================
     * RIWAYAT DISPOSISI PER SURAT
     * =========================
     */
    public function riwayat($suratId)
    {
        $disposisis = Disposisi::with(['dari', 'ke'])
            ->where('surat_masuk_id', $suratId)
            ->orderBy('created_at', 'asc')
            ->get();

        $surat = SuratMasuk::findOrFail($suratId);

        return view('disposisi.riwayat', compact('disposisis', 'surat'));
    }


}

