<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::all();

        // ✅ AUDIT: view list jabatan (opsional)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'view',
                'jabatan',
                null,
                'Melihat daftar jabatan',
                $actor
            );
        }

        return view('jabatan', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required'
        ]);

        $jabatan = Jabatan::create($request->all());

        // ✅ AUDIT: create jabatan
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'create',
                'jabatan',
                $jabatan->id ?? null,
                'Menambahkan jabatan: ' . ($jabatan->nama_jabatan ?? '-'),
                $actor
            );
        }

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $editData = Jabatan::findOrFail($id);
        $jabatans = Jabatan::all();

        // ✅ AUDIT: view edit jabatan (opsional)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'view',
                'jabatan',
                $editData->id ?? null,
                'Membuka form edit jabatan: ' . ($editData->nama_jabatan ?? '-'),
                $actor
            );
        }

        return view('jabatan', compact('jabatans', 'editData'));
    }

    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update($request->all());

        // ✅ AUDIT: update jabatan
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'update',
                'jabatan',
                $jabatan->id ?? null,
                'Memperbarui jabatan: ' . ($jabatan->nama_jabatan ?? '-'),
                $actor
            );
        }

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);

        // ✅ AUDIT: delete jabatan (log dulu sebelum hapus)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'delete',
                'jabatan',
                $jabatan->id ?? null,
                'Menghapus jabatan: ' . ($jabatan->nama_jabatan ?? '-'),
                $actor
            );
        }

        $jabatan->delete();

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus!');
    }
}
