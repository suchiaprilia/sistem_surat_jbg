<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use App\Models\AuditLog; // ✅ tambah audit log
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $suratKeluar = SuratKeluar::with(['jenisSurat'])
            ->orderBy('id_surat_keluar', 'desc')
            ->get();

        $jenisSurat = JenisSurat::all();

        return view('surat-keluar', compact('suratKeluar', 'jenisSurat'));
    }

    public function create()
    {
        return $this->index();
    }

    // ✅ SIMPAN (no_surat_keluar auto + upload file_scan)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination'    => 'required|string|max:255',
            'subject'        => 'required|string|max:255',
            'date'           => 'required|date',
            'kode_surat'     => 'nullable|string|max:10', // default AH
            'requested_by'   => 'nullable|string|max:255',
            'signed_by'      => 'nullable|string|max:255',
            'file_scan'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'id_jenis_surat' => 'nullable|exists:jenis_surat,id_jenis_surat',
        ]);

        // ===== generate nomor surat: 1/AH/JBG/I/2026 =====
        $date = Carbon::parse($validated['date']);
        $tahun = $date->year;
        $bulan = (int) $date->month;

        $romawi = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        $bulanRomawi = $romawi[$bulan];

        $kode = strtoupper($validated['kode_surat'] ?? 'AH');
        $instansi = 'JBG';

        $pattern = "%/{$kode}/{$instansi}/{$bulanRomawi}/{$tahun}";

        $last = SuratKeluar::where('no_surat_keluar', 'like', $pattern)
            ->orderBy('id_surat_keluar', 'desc')
            ->first();

        $nextUrut = 1;
        if ($last && $last->no_surat_keluar) {
            $parts = explode('/', $last->no_surat_keluar);
            $angka = isset($parts[0]) ? (int) $parts[0] : 0;
            $nextUrut = $angka + 1;
        }

        $noSurat = $nextUrut . "/{$kode}/{$instansi}/{$bulanRomawi}/{$tahun}";

        // ===== upload file_scan =====
        if ($request->hasFile('file_scan')) {
            $validated['file_scan'] = $request->file('file_scan')->store('surat-keluar', 'public');
        }

        $validated['no_surat_keluar'] = $noSurat;

        // kalau belum pakai login, hardcode dulu
        $validated['id_user'] = 1;

        $created = SuratKeluar::create($validated);

        // ✅ AUDIT LOG
        AuditLog::tulis(
            'create',
            'surat_keluar',
            $created->id_surat_keluar,
            "Menambah surat keluar. No Surat: {$noSurat}",
            'System'
        );

        return redirect()->route('surat-keluar.index')
            ->with('success', "Surat keluar berhasil ditambahkan. No Surat: {$noSurat}");
    }

    public function edit($id)
    {
        return redirect()->route('surat-keluar.index');
    }

    // ✅ UPDATE (no surat tidak berubah, file boleh diganti)
    public function update(Request $request, $id)
    {
        $data = SuratKeluar::findOrFail($id);

        $validated = $request->validate([
            'destination'    => 'required|string|max:255',
            'subject'        => 'required|string|max:255',
            'date'           => 'required|date',
            'requested_by'   => 'nullable|string|max:255',
            'signed_by'      => 'nullable|string|max:255',
            'file_scan'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'id_jenis_surat' => 'nullable|exists:jenis_surat,id_jenis_surat',
        ]);

        // kalau upload file baru, hapus file lama
        if ($request->hasFile('file_scan')) {
            if ($data->file_scan && Storage::disk('public')->exists($data->file_scan)) {
                Storage::disk('public')->delete($data->file_scan);
            }
            $validated['file_scan'] = $request->file('file_scan')->store('surat-keluar', 'public');
        }

        $data->update($validated);

        // ✅ AUDIT LOG
        AuditLog::tulis(
            'update',
            'surat_keluar',
            $data->id_surat_keluar,
            "Mengubah surat keluar. No Surat: {$data->no_surat_keluar}",
            'System'
        );

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Data surat keluar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = SuratKeluar::findOrFail($id);

        // ✅ AUDIT LOG (tulis dulu sebelum delete)
        AuditLog::tulis(
            'delete',
            'surat_keluar',
            $data->id_surat_keluar,
            "Menghapus surat keluar. No Surat: {$data->no_surat_keluar}",
            'System'
        );

        if ($data->file_scan && Storage::disk('public')->exists($data->file_scan)) {
            Storage::disk('public')->delete($data->file_scan);
        }

        $data->delete();

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Data surat keluar berhasil dihapus!');
    }
}
