<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Agenda;
use App\Models\AuditLog;


class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // ✅ ambil agenda hari ini (maks 5)
        $agenda_hari_ini = Agenda::whereDate('tanggal_mulai', $today)
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        // ✅ (opsional) ambil agenda terdekat mulai dari sekarang (next 5)
        $agenda_terdekat = Agenda::where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        // ✅ ARSIP DIGITAL (sederhana): surat yang punya file upload
        $digitalMasuk  = SuratMasuk::whereNotNull('file_surat')->count();
        $digitalKeluar = SuratKeluar::whereNotNull('file_scan')->count();
        $totalDigital  = $digitalMasuk + $digitalKeluar;

        $totalAll = SuratMasuk::count() + SuratKeluar::count();
        $persenDigital = $totalAll > 0 ? round(($totalDigital / $totalAll) * 100, 1) : 0;

        // === STATISTIK TANPA STATUS ===
        $stats = [
            'masuk_hari_ini'   => SuratMasuk::whereDate('tanggal_terima', $today)->count(),
            'keluar_hari_ini'  => SuratKeluar::whereDate('date', $today)->count(),
            'pending'          => SuratMasuk::count(),
            'urgent'           => 0,
            'terlambat'        => 0,
            'urgent_24j'       => 0,
            'total_masuk'      => SuratMasuk::count(),
            'total_keluar'     => SuratKeluar::count(),
            'efisiensi'        => 0,
            'rata_waktu'       => 0,

            // ✅ aktifkan arsip digital
            'digital'          => $totalDigital,
            'persen_digital'   => $persenDigital,
        ];

        // === Tampilkan 5 surat terbaru (berdasarkan tanggal_terima) ===
        $surat_pending = SuratMasuk::orderBy('tanggal_terima', 'desc')
            ->take(5)
            ->get();

        // === CHART: 6 BULAN TERAKHIR ===
        $chartLabels = [];
        $chartMasuk  = [];
        $chartKeluar = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $year = $month->year;
            $monthNum = $month->month;

            $chartLabels[] = $month->format('M');

            $chartMasuk[] = SuratMasuk::whereYear('tanggal_terima', $year)
                ->whereMonth('tanggal_terima', $monthNum)
                ->count();

            $chartKeluar[] = SuratKeluar::whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->count();
        }

        // ✅ PIE CHART: Distribusi Jenis Surat (REAL dari database)
        $jenisQuery = SuratMasuk::with('jenisSurat')
            ->get()
            ->groupBy(function ($s) {
                return $s->jenisSurat->jenis_surat ?? 'Lainnya';
            })
            ->map->count();

        $jenisLabels = $jenisQuery->keys()->values();
        $jenisData   = $jenisQuery->values()->values();

        // ✅ ambil 5 audit log terbaru
        $audit_logs = AuditLog::orderBy('created_at', 'desc')
         ->take(5)
         ->get();


        return view('index', compact(
            'stats',
            'surat_pending',
            'chartLabels',
            'chartMasuk',
            'chartKeluar',
            'jenisLabels',
            'jenisData',
            'agenda_hari_ini',
            'agenda_terdekat',
            'audit_logs'
        ));
    }
}
