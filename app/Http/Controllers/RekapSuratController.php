<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapSuratExport;

class RekapSuratController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        // ✅ AUDIT: lihat rekap (opsional)
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            $range = ($start && $end) ? "Range: {$start} s/d {$end}" : "Tanpa filter tanggal";
            AuditLog::tulis(
                'view',
                'rekap_surat',
                null,
                "Melihat halaman rekap surat. {$range}",
                $actor
            );
        }

        // Surat Masuk
        $suratMasuk = SuratMasuk::with('jenisSurat')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('tanggal_terima', [$start, $end]);
            })
            ->get();

        // Surat Keluar
        $suratKeluar = SuratKeluar::with('jenisSurat')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('date', [$start, $end]);
            })
            ->get();

        // Summary
        $totalMasuk  = $suratMasuk->count();
        $totalKeluar = $suratKeluar->count();
        $totalSurat  = $totalMasuk + $totalKeluar;

        // Rekap per Jenis
        $rekapJenisMasuk = SuratMasuk::selectRaw('id_jenis_surat, COUNT(*) as total')
            ->groupBy('id_jenis_surat')
            ->with('jenisSurat')
            ->get();

        $rekapJenisKeluar = SuratKeluar::selectRaw('id_jenis_surat, COUNT(*) as total')
            ->groupBy('id_jenis_surat')
            ->with('jenisSurat')
            ->get();

        return view('rekap-surat', compact(
            'suratMasuk',
            'suratKeluar',
            'totalMasuk',
            'totalKeluar',
            'totalSurat',
            'rekapJenisMasuk',
            'rekapJenisKeluar'
        ));
    }

    // =======================
    // EXPORT PDF
    // =======================
    public function exportPdf(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

        // ✅ AUDIT: export pdf
        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            $range = ($start && $end) ? "Range: {$start} s/d {$end}" : "Tanpa filter tanggal";
            AuditLog::tulis(
                'export_pdf',
                'rekap_surat',
                null,
                "Export rekap surat ke PDF. {$range}",
                $actor
            );
        }

        $suratMasuk = SuratMasuk::with('jenisSurat')
            ->when($start && $end, fn ($q) =>
                $q->whereBetween('tanggal_terima', [$start, $end])
            )->get();

        $suratKeluar = SuratKeluar::with('jenisSurat')
            ->when($start && $end, fn ($q) =>
                $q->whereBetween('date', [$start, $end])
            )->get();

        $pdf = Pdf::loadView('rekap-surat-pdf', compact(
            'suratMasuk',
            'suratKeluar'
        ));

        return $pdf->download('rekap-surat.pdf');
    }

    // =======================
    // EXPORT EXCEL
    // =======================
    public function exportExcel(Request $request)
    {
        // ✅ AUDIT: export excel
        $start = $request->start_date;
        $end   = $request->end_date;

        if (class_exists(AuditLog::class) && method_exists(AuditLog::class, 'tulis')) {
            $actor = Auth::user()->name ?? 'System';
            $range = ($start && $end) ? "Range: {$start} s/d {$end}" : "Tanpa filter tanggal";
            AuditLog::tulis(
                'export_excel',
                'rekap_surat',
                null,
                "Export rekap surat ke Excel. {$range}",
                $actor
            );
        }

        return Excel::download(
            new RekapSuratExport($request),
            'rekap-surat.xlsx'
        );
    }
}
