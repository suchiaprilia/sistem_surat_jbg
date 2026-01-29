<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Agenda;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // ✅ Agenda hari ini (maks 5)
        $agenda_hari_ini = Agenda::whereDate('tanggal_mulai', $today)
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        // ✅ Agenda terdekat (opsional)
        $agenda_terdekat = Agenda::where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get();

        // ✅ Arsip Digital (surat yang punya file upload)
        $digitalMasuk  = SuratMasuk::whereNotNull('file_surat')->count();
        $digitalKeluar = SuratKeluar::whereNotNull('file_scan')->count();
        $totalDigital  = $digitalMasuk + $digitalKeluar;

        $totalAll = SuratMasuk::count() + SuratKeluar::count();
        $persenDigital = $totalAll > 0 ? round(($totalDigital / $totalAll) * 100, 1) : 0;

        // ✅ Statistik
        $stats = [
            'masuk_hari_ini'  => SuratMasuk::whereDate('tanggal_terima', $today)->count(),
            'keluar_hari_ini' => SuratKeluar::whereDate('date', $today)->count(),
            'pending'         => SuratMasuk::count(),

            'urgent'      => 0,
            'terlambat'   => 0,
            'urgent_24j'  => 0,
            'efisiensi'   => 0,
            'rata_waktu'  => 0,

            'total_masuk'  => SuratMasuk::count(),
            'total_keluar' => SuratKeluar::count(),

            'digital'        => $totalDigital,
            'persen_digital' => $persenDigital,
        ];

        // ✅ 5 surat masuk terbaru
        $surat_pending = SuratMasuk::orderBy('tanggal_terima', 'desc')
            ->take(5)
            ->get();

        // ✅ Chart surat masuk/keluar 6 bulan terakhir
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

        // ✅ DISTRIBUSI JENIS SURAT (GABUNGAN SURAT MASUK + SURAT KELUAR)
        $jenisRows = DB::query()
            ->fromSub(function ($q) {
                $q->select('id_jenis_surat')->from('surat_masuks')
                    ->unionAll(
                        DB::table('surat_keluars')->select('id_jenis_surat')
                    );
            }, 'gabungan')
            ->leftJoin('jenis_surat', 'gabungan.id_jenis_surat', '=', 'jenis_surat.id_jenis_surat')
            ->selectRaw("COALESCE(jenis_surat.jenis_surat, 'Lainnya') as nama_jenis, COUNT(*) as total")
            ->groupBy('nama_jenis')
            ->orderByDesc('total')
            ->get();

        $jenisLabels = $jenisRows->pluck('nama_jenis')->toArray();
        $jenisData   = $jenisRows->pluck('total')->toArray();

        // ✅ Audit log (kalau kamu sudah punya tabelnya, kalau belum aman karena try-catch)
        $audit_logs = collect();
        try {
            $audit_logs = DB::table('audit_logs')
                ->orderBy('id', 'desc')
                ->take(5)
                ->get();
        } catch (\Throwable $e) {
            // kalau tabel audit_logs belum ada, biarkan kosong
        }

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
