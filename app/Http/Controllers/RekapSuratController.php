<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapSuratExport;

class RekapSuratController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->start_date;
        $end   = $request->end_date;

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
        return Excel::download(
            new RekapSuratExport($request),
            'rekap-surat.xlsx'
        );
    }
}
