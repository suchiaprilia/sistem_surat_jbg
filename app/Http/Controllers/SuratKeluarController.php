<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

    // ✅ SIMPAN (No Surat manual)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat_keluar' => 'required|string|max:255',
            'destination'     => 'required|string|max:255',
            'subject'         => 'required|string|max:255',
            'date'            => 'required|date',
            'requested_by'    => 'nullable|string|max:255',
            'signed_by'       => 'nullable|string|max:255',
            'file_scan'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'id_jenis_surat'  => 'nullable|exists:jenis_surat,id_jenis_surat',
        ]);

        if ($request->hasFile('file_scan')) {
            $validated['file_scan'] = $request->file('file_scan')->store('surat-keluar', 'public');
        }

        // ✅ pakai user login kalau ada
        $validated['id_user'] = Auth::id() ?? 1;

        $created = SuratKeluar::create($validated);

        // ✅ AUDITLOG
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            AuditLog::tulis(
                'create',
                'surat_keluar',
                $created->id_surat_keluar,
                "Menambah surat keluar. No Surat: " . ($created->no_surat_keluar ?? '-'),
                $actor
            );
        }

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil ditambahkan!');
    }

    public function edit($id)
    {
        return redirect()->route('surat-keluar.index');
    }

    // ✅ UPDATE (No Surat bisa diedit)
    public function update(Request $request, $id)
    {
        $data = SuratKeluar::findOrFail($id);

        $validated = $request->validate([
            'no_surat_keluar' => 'required|string|max:255',
            'destination'     => 'required|string|max:255',
            'subject'         => 'required|string|max:255',
            'date'            => 'required|date',
            'requested_by'    => 'nullable|string|max:255',
            'signed_by'       => 'nullable|string|max:255',
            'file_scan'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'id_jenis_surat'  => 'nullable|exists:jenis_surat,id_jenis_surat',
        ]);

        if ($request->hasFile('file_scan')) {
            if ($data->file_scan && Storage::disk('public')->exists($data->file_scan)) {
                Storage::disk('public')->delete($data->file_scan);
            }
            $validated['file_scan'] = $request->file('file_scan')->store('surat-keluar', 'public');
        }

        $data->update($validated);

        // ✅ AUDITLOG
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            AuditLog::tulis(
                'update',
                'surat_keluar',
                $data->id_surat_keluar,
                "Mengubah surat keluar. No Surat: " . ($data->no_surat_keluar ?? '-'),
                $actor
            );
        }

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Data surat keluar berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = SuratKeluar::findOrFail($id);

        // ✅ AUDITLOG (log dulu sebelum delete)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            AuditLog::tulis(
                'delete',
                'surat_keluar',
                $data->id_surat_keluar,
                "Menghapus surat keluar. No Surat: " . ($data->no_surat_keluar ?? '-'),
                $actor
            );
        }

        if ($data->file_scan && Storage::disk('public')->exists($data->file_scan)) {
            Storage::disk('public')->delete($data->file_scan);
        }

        $data->delete();

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Data surat keluar berhasil dihapus!');
    }

    // ================================
    // 🔥 METHOD UNTUK BUKA FILE SCAN (opsional)
    // ================================
    public function lihatFile($id)
    {
        $surat = SuratKeluar::findOrFail($id);

        if (
            !$surat->file_scan ||
            !Storage::disk('public')->exists($surat->file_scan)
        ) {
            abort(404, 'File tidak ditemukan');
        }

        // ✅ AUDITLOG VIEW FILE
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            AuditLog::tulis(
                'view_file',
                'surat_keluar',
                $surat->id_surat_keluar,
                "Melihat file scan surat keluar. No Surat: " . ($surat->no_surat_keluar ?? '-'),
                $actor
            );
        }

        return response()->file(
            storage_path('app/public/' . $surat->file_scan),
            [
                'Content-Type' => Storage::disk('public')->mimeType($surat->file_scan)
            ]
        );
    }

    // optional: kalau ada route lama ->file()
    public function file($id)
    {
        return $this->lihatFile($id);
    }
}
