<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use App\Models\AuditLog;
use Illuminate\Http\Request;
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

    // ✅ SIMPAN (tanpa nomor otomatis)
    public function store(Request $request)
    {
        $validated = $request->validate([
            // kalau kamu mau nomor manual, aktifkan ini:
            // 'no_surat_keluar' => 'nullable|string|max:255',

            'destination'    => 'required|string|max:255',
            'subject'        => 'required|string|max:255',
            'date'           => 'required|date',
            'requested_by'   => 'nullable|string|max:255',
            'signed_by'      => 'nullable|string|max:255',
            'file_scan'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'id_jenis_surat' => 'nullable|exists:jenis_surat,id_jenis_surat',
        ]);

        // upload file_scan
        if ($request->hasFile('file_scan')) {
            $validated['file_scan'] = $request->file('file_scan')->store('surat-keluar', 'public');
        }

        // hardcode dulu kalau belum login
        $validated['id_user'] = 1;

        // kalau field no_surat_keluar di database wajib diisi,
        // kamu bisa isi default sementara:
        if (!isset($validated['no_surat_keluar'])) {
            $validated['no_surat_keluar'] = '-';
        }

        $created = SuratKeluar::create($validated);

        // audit log (kalau model AuditLog kamu ada method tulis)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            AuditLog::tulis(
                'create',
                'surat_keluar',
                $created->id_surat_keluar,
                "Menambah surat keluar. No Surat: " . ($created->no_surat_keluar ?? '-'),
                'System'
            );
        }

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil ditambahkan!');
    }

    public function edit($id)
    {
        return redirect()->route('surat-keluar.index');
    }

    // ✅ UPDATE
    public function update(Request $request, $id)
    {
        $data = SuratKeluar::findOrFail($id);

        $validated = $request->validate([
            // kalau mau nomor manual bisa update juga:
            // 'no_surat_keluar' => 'nullable|string|max:255',

            'destination'    => 'required|string|max:255',
            'subject'        => 'required|string|max:255',
            'date'           => 'required|date',
            'requested_by'   => 'nullable|string|max:255',
            'signed_by'      => 'nullable|string|max:255',
            'file_scan'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'id_jenis_surat' => 'nullable|exists:jenis_surat,id_jenis_surat',
        ]);

        if ($request->hasFile('file_scan')) {
            if ($data->file_scan && Storage::disk('public')->exists($data->file_scan)) {
                Storage::disk('public')->delete($data->file_scan);
            }
            $validated['file_scan'] = $request->file('file_scan')->store('surat-keluar', 'public');
        }

        $data->update($validated);

        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            AuditLog::tulis(
                'update',
                'surat_keluar',
                $data->id_surat_keluar,
                "Mengubah surat keluar. No Surat: " . ($data->no_surat_keluar ?? '-'),
                'System'
            );
        }

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Data surat keluar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = SuratKeluar::findOrFail($id);

        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            AuditLog::tulis(
                'delete',
                'surat_keluar',
                $data->id_surat_keluar,
                "Menghapus surat keluar. No Surat: " . ($data->no_surat_keluar ?? '-'),
                'System'
            );
        }

        if ($data->file_scan && Storage::disk('public')->exists($data->file_scan)) {
            Storage::disk('public')->delete($data->file_scan);
        }

        $data->delete();

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Data surat keluar berhasil dihapus!');
    }
}
