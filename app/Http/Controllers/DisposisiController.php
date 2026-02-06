<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    /**
     * ==================================================
     * INBOX DISPOSISI
     * ==================================================
     */
    public function index()
{
    $karyawan = auth()->user()->karyawan;

    if (!$karyawan) {
        abort(403, 'User belum terhubung ke data karyawan');
    }

    $disposisis = Disposisi::with(['suratMasuk', 'dari'])
        ->where('ke_karyawan_id', $karyawan->id_karyawan)
        ->whereIn('status', ['baru', 'dibaca'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('disposisi.index', compact('disposisis'));
}


    /**
     * ==================================================
     * FORM BUAT DISPOSISI
     * ==================================================
     */
   public function create($suratMasukId)
{
    $karyawanLogin = auth()->user()->karyawan;

    if (!$karyawanLogin) {
        abort(403, 'User belum terhubung ke data karyawan');
    }

    $surat = SuratMasuk::findOrFail($suratMasukId);

    // ===============================
    // LOGIKA TUJUAN DISPOSISI
    // ===============================

    if ($karyawanLogin->role === 'admin') {
    // admin → semua kecuali diri sendiri
    $karyawans = Karyawan::where('id_karyawan', '!=', $karyawanLogin->id_karyawan)->get();
}
elseif ($karyawanLogin->role === 'pimpinan') {
    // pimpinan → pimpinan lain + staff
    $karyawans = Karyawan::whereIn('role', ['pimpinan', 'user'])
        ->where('id_karyawan', '!=', $karyawanLogin->id_karyawan)
        ->get();
}
elseif ($karyawanLogin->role === 'user') {
    // staff → staff lain
    $karyawans = Karyawan::where('role', 'user')
        ->where('id_karyawan', '!=', $karyawanLogin->id_karyawan)
        ->get();
}
else {
    abort(403);
}
    return view('disposisi.create', compact('surat', 'karyawans'));
}



    /**
     * ==================================================
     * SIMPAN DISPOSISI
     * ==================================================
     */
    public function store(Request $request)
{
    $karyawan = auth()->user()->karyawan;

    if (!$karyawan) {
        abort(403);
    }

    $request->validate([
        'surat_masuk_id' => 'required|exists:surat_masuks,id',
        'ke_karyawan_id' => 'required|exists:karyawans,id_karyawan',
        'instruksi'      => 'required|string',
    ]);

    $tujuan = Karyawan::findOrFail($request->ke_karyawan_id);

    // admin bebas
    if ($karyawan->role === 'admin') {
        // do nothing
    }
    // pimpinan tidak boleh ke admin
    elseif ($karyawan->role === 'pimpinan' && $tujuan->role === 'admin') {
        abort(403);
    }
    // staff hanya ke staff
    elseif ($karyawan->role === 'user' && $tujuan->role !== 'user') {
        abort(403);
    }

    Disposisi::create([
        'surat_masuk_id'   => $request->surat_masuk_id,
        'dari_karyawan_id' => $karyawan->id_karyawan,
        'ke_karyawan_id'   => $tujuan->id_karyawan,
        'instruksi'        => $request->instruksi,
        'parent_id'        => $request->disposisi_lama_id ?? null,
        'status'           => 'baru',
    ]);

    return redirect()->route('disposisi.index');
}




    public function forward($id)
{
    $karyawan = auth()->user()->karyawan;

    if (!$karyawan) {
        abort(403);
    }

    $disposisi = Disposisi::findOrFail($id);

    if ($karyawan->role === 'admin') {
        $karyawans = Karyawan::where('id_karyawan', '!=', $karyawan->id_karyawan)->get();
    }
    elseif ($karyawan->role === 'pimpinan') {
        $karyawans = Karyawan::whereIn('role', ['pimpinan', 'user'])
            ->where('id_karyawan', '!=', $karyawan->id_karyawan)
            ->get();
    }
    else { // staff
        $karyawans = Karyawan::where('role', 'user')
            ->where('id_karyawan', '!=', $karyawan->id_karyawan)
            ->get();
    }

    return view('disposisi.forward', compact('disposisi', 'karyawans'));
}


    /**
     * ==================================================
     * TANDAI DIBACA
     * ==================================================
     */
    public function markRead($id)
    {
        $karyawan = auth()->user()->karyawan;

        if (!$karyawan) {
            abort(403);
        }

        $disposisi = Disposisi::findOrFail($id);

        if ($disposisi->ke_karyawan_id !== $karyawan->id_karyawan) {
            abort(403);
        }

        $disposisi->update(['status' => 'dibaca']);

        return back();
    }

    /**
     * ==================================================
     * TANDAI SELESAI
     * ==================================================
     */
    public function markDone($id)
    {
        $karyawan = auth()->user()->karyawan;

        if (!$karyawan) {
            abort(403);
        }

        $disposisi = Disposisi::findOrFail($id);

        if ($disposisi->ke_karyawan_id !== $karyawan->id_karyawan) {
            abort(403);
        }

        $disposisi->update(['status' => 'selesai']);

        return back()->with('success', 'Disposisi telah diselesaikan');
    }

    /**
     * ==================================================
     * RIWAYAT DISPOSISI
     * ==================================================
     */
    public function riwayat($suratId)
    {
        $karyawan = auth()->user()->karyawan;

        if (!$karyawan || !in_array($karyawan->role, ['admin', 'pimpinan'])) {
            abort(403);
        }

        $surat = SuratMasuk::findOrFail($suratId);

        $disposisis = Disposisi::with(['dari', 'ke'])
            ->where('surat_masuk_id', $suratId)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('disposisi.riwayat', compact('disposisis', 'surat'));
    }
}
