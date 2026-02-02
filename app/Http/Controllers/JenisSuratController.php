<?php

namespace App\Http\Controllers;

use App\Models\JenisSurat;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class JenisSuratController extends Controller
{
    public function index()
    {
        $jenisSurats = JenisSurat::orderBy('created_at', 'desc')->get();

        // ✅ AUDIT: view list jenis surat (opsional)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'view',
                'jenis_surat',
                null,
                'Melihat daftar jenis surat',
                $actor
            );
        }

        return view('jenis-surat', compact('jenisSurats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
        ]);

        $jenis = JenisSurat::create($request->only('jenis_surat'));

        // ✅ AUDIT: create jenis surat
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'create',
                'jenis_surat',
                $jenis->id ?? null,
                'Menambahkan jenis surat: ' . ($jenis->jenis_surat ?? '-'),
                $actor
            );
        }

        return redirect()->route('jenis-surat.index')
            ->with('success', 'Jenis surat berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_surat' => 'required|string|max:255',
        ]);

        $jenis = JenisSurat::findOrFail($id);
        $jenis->update($request->only('jenis_surat'));

        // ✅ AUDIT: update jenis surat
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'update',
                'jenis_surat',
                $jenis->id ?? null,
                'Memperbarui jenis surat: ' . ($jenis->jenis_surat ?? '-'),
                $actor
            );
        }

        return redirect()->route('jenis-surat.index')
            ->with('success', 'Jenis surat berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jenis = JenisSurat::findOrFail($id);

        // ✅ AUDIT: delete jenis surat (log dulu sebelum hapus)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'delete',
                'jenis_surat',
                $jenis->id ?? null,
                'Menghapus jenis surat: ' . ($jenis->jenis_surat ?? '-'),
                $actor
            );
        }

        $jenis->delete();

        return redirect()->route('jenis-surat.index')
            ->with('success', 'Jenis surat berhasil dihapus!');
    }

    // Redirect untuk aksi yang tidak digunakan
    public function create()
    {
        return redirect()->route('jenis-surat.index');
    }

    public function edit($id)
    {
        return redirect()->route('jenis-surat.index');
    }

    public function show($id)
    {
        return redirect()->route('jenis-surat.index');
    }
}
