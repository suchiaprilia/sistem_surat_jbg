<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\JenisSurat;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index()
    {
        $suratMasuk = SuratMasuk::with('jenisSurat')->get();
        $jenisSurat = JenisSurat::all();

        return view('surat-masuk', compact('suratMasuk', 'jenisSurat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required',
            'pengirim' => 'required',
            'subject' => 'required',
            'tujuan' => 'required',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        if ($request->hasFile('file_surat')) {
            $validated['file_surat'] = $request
                ->file('file_surat')
                ->store('surat-masuk', 'public');
        }

        $created = SuratMasuk::create($validated);

        AuditLog::tulis(
            'create',
            'surat_masuk',
            $created->id,
            "Menambah surat masuk. No Surat: {$created->no_surat}",
            'System'
        );

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = SuratMasuk::findOrFail($id);

        $validated = $request->validate([
            'no_surat' => 'required',
            'tanggal' => 'required|date',
            'tanggal_terima' => 'required|date',
            'penerima' => 'required',
            'pengirim' => 'required',
            'subject' => 'required',
            'tujuan' => 'required',
            'id_jenis_surat' => 'required|exists:jenis_surat,id_jenis_surat',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        if ($request->hasFile('file_surat')) {
            if ($item->file_surat && Storage::disk('public')->exists($item->file_surat)) {
                Storage::disk('public')->delete($item->file_surat);
            }

            $validated['file_surat'] = $request
                ->file('file_surat')
                ->store('surat-masuk', 'public');
        }

        $item->update($validated);

        AuditLog::tulis(
            'update',
            'surat_masuk',
            $item->id,
            "Mengubah surat masuk. No Surat: {$item->no_surat}",
            'System'
        );

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = SuratMasuk::findOrFail($id);

        AuditLog::tulis(
            'delete',
            'surat_masuk',
            $item->id,
            "Menghapus surat masuk. No Surat: {$item->no_surat}",
            'System'
        );

        if ($item->file_surat && Storage::disk('public')->exists($item->file_surat)) {
            Storage::disk('public')->delete($item->file_surat);
        }

        $item->delete();

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus.');
    }

    // ================================
    // 🔥 BUKA FILE + TANDAI SUDAH DIBACA
    // ================================
    public function lihatFile($id)
    {
        $surat = SuratMasuk::findOrFail($id);

        // tandai sudah dibaca
        if ($surat->is_read == 0) {
            $surat->update(['is_read' => 1]);
        }

        if (
            !$surat->file_surat ||
            !Storage::disk('public')->exists($surat->file_surat)
        ) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file(
            storage_path('app/public/' . $surat->file_surat),
            [
                'Content-Type' => Storage::disk('public')->mimeType($surat->file_surat)
            ]
        );
    }
}
