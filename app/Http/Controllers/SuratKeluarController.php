<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use App\Models\NomorSurat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $suratKeluar = SuratKeluar::with(['jenisSurat', 'nomorSurat'])
            ->orderBy('id_surat_keluar', 'desc')
            ->get();

        $jenisSurat = JenisSurat::all();

        return view('surat-keluar', compact('suratKeluar', 'jenisSurat'));
    }

    // optional (kalau route create kepakai)
    public function create()
    {
        return $this->index();
    }

    // ✅ SIMPAN (no_surat_keluar auto + simpan ke nomor_surats + upload file_scan)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination'   => 'required|string|max:255',
            'subject'       => 'required|string|max:255',
            'date'          => 'required|date',
            'kode_surat'    => 'nullable|string|max:10', // default AH
            'requested_by'  => 'nullable|string|max:255',
            'signed_by'     => 'nullable|string|max:255',
            'file_scan'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
            'id_jenis_surat'=> 'nullable|exists:jenis_surat,id_jenis_surat',
        ]);

        // ===== generate nomor surat: 388/AH/JBG/IX/2025 (auto naik) =====
        $date = Carbon::parse($validated['date']);
        $tahun = $date->year;
        $bulan = (int) $date->month;

        $romawi = [1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'];
        $bulanRomawi = $romawi[$bulan];

        $kode = strtoupper($validated['kode_surat'] ?? 'AH');
        $instansi = 'JBG';

        // reset per bulan+tahun+kode
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

        // simpan juga ke nomor_surats (arsip)
        $nomor = NomorSurat::firstOrCreate([
            'nomor' => $noSurat
        ]);

        // ===== upload file_scan =====
        if ($request->hasFile('file_scan')) {
            $validated['file_scan'] = $request->file('file_scan')->store('surat-keluar', 'public');
        }

        $validated['no_surat_keluar'] = $noSurat;
        $validated['id_number_surat'] = $nomor->id;

        // kalau belum ada auth, tetap hardcode
        $validated['id_user'] = 1;

        SuratKeluar::create($validated);

        return redirect()->route('surat-keluar.index')
            ->with('success', "Surat keluar berhasil ditambahkan. No Surat: {$noSurat}");
    }

    public function edit($id)
    {
        // kamu pakai modal, jadi biasanya edit tidak dipakai
        return redirect()->route('surat-keluar.index');
    }

    // ✅ UPDATE (no surat tidak berubah, file boleh diganti)
    public function update(Request $request, $id)
    {
        $data = SuratKeluar::findOrFail($id);

        $validated = $request->validate([
            'destination'   => 'required|string|max:255',
            'subject'       => 'required|string|max:255',
            'date'          => 'required|date',
            'requested_by'  => 'nullable|string|max:255',
            'signed_by'     => 'nullable|string|max:255',
            'file_scan'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'id_jenis_surat'=> 'nullable|exists:jenis_surat,id_jenis_surat',
        ]);

        // kalau upload file baru, hapus file lama
        if ($request->hasFile('file_scan')) {
            if ($data->file_scan && Storage::disk('public')->exists($data->file_scan)) {
                Storage::disk('public')->delete($data->file_scan);
            }
            $validated['file_scan'] = $request->file('file_scan')->store('surat-keluar', 'public');
        }

        // ❗ no_surat_keluar tetap (tidak diubah)
        $data->update($validated);

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Data surat keluar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = SuratKeluar::findOrFail($id);

        // hapus file scan kalau ada
        if ($data->file_scan && Storage::disk('public')->exists($data->file_scan)) {
            Storage::disk('public')->delete($data->file_scan);
        }

        $data->delete();

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Data surat keluar berhasil dihapus!');
    }
}
