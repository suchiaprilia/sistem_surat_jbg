<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()->orderBy('id', 'desc');

        // filter opsional
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }
        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        // ✅ filter tanggal (start_date - end_date)
        // bekerja kalau dua-duanya diisi
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('audit-log', compact('logs'));
    }
}
