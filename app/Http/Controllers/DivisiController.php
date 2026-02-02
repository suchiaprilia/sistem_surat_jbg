<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    public function index()
    {
        $divisis = Divisi::all();

        // ✅ AUDIT: view list divisi (opsional)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'view',
                'divisi',
                null,
                'Melihat daftar divisi',
                $actor
            );
        }

        return view('divisi', compact('divisis'));
    }

    public function create()
    {
        return view('divisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_divisi' => 'required',
        ]);

        $divisi = Divisi::create($request->all());

        // ✅ AUDIT: create divisi
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'create',
                'divisi',
                $divisi->id ?? null,
                'Menambahkan divisi: ' . ($divisi->nama_divisi ?? '-'),
                $actor
            );
        }

        return redirect()->route('divisi.index')
            ->with('success', 'Divisi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);

        // ✅ AUDIT: view edit divisi (opsional)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'view',
                'divisi',
                $divisi->id ?? null,
                'Membuka form edit divisi: ' . ($divisi->nama_divisi ?? '-'),
                $actor
            );
        }

        return view('divisi.edit', compact('divisi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_divisi' => 'required',
        ]);

        $divisi = Divisi::findOrFail($id);
        $divisi->update($request->all());

        // ✅ AUDIT: update divisi
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'update',
                'divisi',
                $divisi->id ?? null,
                'Memperbarui divisi: ' . ($divisi->nama_divisi ?? '-'),
                $actor
            );
        }

        return redirect()->route('divisi.index')
            ->with('success', 'Divisi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);

        // ✅ AUDIT: delete divisi (log dulu sebelum hapus)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = auth()->user()->name ?? 'System';
            AuditLog::tulis(
                'delete',
                'divisi',
                $divisi->id ?? null,
                'Menghapus divisi: ' . ($divisi->nama_divisi ?? '-'),
                $actor
            );
        }

        $divisi->delete();

        return redirect()->route('divisi.index')
            ->with('success', 'Divisi berhasil dihapus!');
    }
}
