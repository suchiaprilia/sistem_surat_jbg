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

        $logs = $query->paginate(20)->withQueryString();

        return view('audit-log', compact('logs'));
    }
}
