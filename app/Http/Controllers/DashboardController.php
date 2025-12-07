<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // === STATISTIK TANPA STATUS ===
        $stats = [
            'masuk_hari_ini' => SuratMasuk::whereDate('tanggal_terima', $today)->count(),
            'keluar_hari_ini' => SuratKeluar::whereDate('date', $today)->count(),
            'pending' => SuratMasuk::count(), // ← semua surat dianggap "pending"
            'urgent' => 0, // tetap 0 karena belum ada deadline
            'terlambat' => 0,
            'urgent_24j' => 0,
            'total_masuk' => SuratMasuk::count(),
            'total_keluar' => SuratKeluar::count(),
            'efisiensi' => 0, // tidak bisa dihitung tanpa status
            'rata_waktu' => 0,
            'digital' => 0,
            'persen_digital' => 0,
        ];

        // === Tampilkan 5 surat terbaru (berdasarkan tanggal_terima) ===
        $surat_pending = SuratMasuk::orderBy('tanggal_terima', 'desc')
                                   ->take(5)
                                   ->get();

        // === CHART: 6 BULAN TERAKHIR ===
        $chartLabels = [];
        $chartMasuk = [];
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

        $jenisLabels = ['Undangan', 'Laporan', 'Permohonan'];
        $jenisData = [30, 45, 25];

        return view('index', compact(
            'stats',
            'surat_pending',
            'chartLabels',
            'chartMasuk',
            'chartKeluar',
            'jenisLabels',
            'jenisData'
        ));
    }
}
