<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\RekapSuratController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\AuditLogController;

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.redirect');

/*
|--------------------------------------------------------------------------
| REKAP SURAT
|--------------------------------------------------------------------------
*/
Route::get('/rekap-surat', [RekapSuratController::class, 'index'])->name('rekap-surat');
Route::get('/rekap-surat/export/pdf', [RekapSuratController::class, 'exportPdf'])
    ->name('rekap-surat.export.pdf');
Route::get('/rekap-surat/export/excel', [RekapSuratController::class, 'exportExcel'])
    ->name('rekap-surat.export.excel');

/*
|--------------------------------------------------------------------------
| TEST PAGE
|--------------------------------------------------------------------------
*/
Route::get('/test', function () {
    return view('test');
});

/*
|--------------------------------------------------------------------------
| SURAT MASUK - VIEW FILE (HARUS DI ATAS RESOURCE)
|--------------------------------------------------------------------------
*/
Route::get(
    '/surat-masuk/{id}/file',
    [SuratMasukController::class, 'lihatFile']
)->name('surat-masuk.file');

/*
|--------------------------------------------------------------------------
| CRUD RESOURCE
|--------------------------------------------------------------------------
*/
Route::resource('surat-masuk', SuratMasukController::class);
Route::resource('surat-keluar', SuratKeluarController::class);
Route::resource('divisi', DivisiController::class);
Route::resource('jabatan', JabatanController::class);
Route::resource('karyawan', KaryawanController::class);
Route::resource('jenis-surat', JenisSuratController::class)->names([
    'index' => 'jenis-surat.index',
    'store' => 'jenis-surat.store',
    'update' => 'jenis-surat.update',
    'destroy' => 'jenis-surat.destroy',
]);

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
Route::get('/search', [SearchController::class, 'index'])->name('search');

/*
|--------------------------------------------------------------------------
| AGENDA
|--------------------------------------------------------------------------
*/
Route::prefix('agenda')->name('agenda.')->group(function () {
    Route::get('/', [AgendaController::class, 'index'])->name('index');
    Route::post('/', [AgendaController::class, 'store'])->name('store');
    Route::get('/{agenda}', [AgendaController::class, 'show'])->name('show');
    Route::put('/{agenda}', [AgendaController::class, 'update'])->name('update');
    Route::delete('/{agenda}', [AgendaController::class, 'destroy'])->name('destroy');
    Route::post('/{agenda}/done', [AgendaController::class, 'markDone'])->name('done');
});

/*
|--------------------------------------------------------------------------
| DISPOSISI
|--------------------------------------------------------------------------
*/
Route::prefix('disposisi')->name('disposisi.')->group(function () {
    Route::get('/', [DisposisiController::class, 'index'])->name('index');
    Route::get('/create/{surat}', [DisposisiController::class, 'create'])->name('create');
    Route::post('/store', [DisposisiController::class, 'store'])->name('store');

    Route::get('/{id}/teruskan', [DisposisiController::class, 'forward'])
        ->name('teruskan');

    Route::get('/surat/{surat}/riwayat', [DisposisiController::class, 'riwayat'])
        ->name('riwayat');

    Route::post('/{id}/dibaca', [DisposisiController::class, 'markRead'])->name('dibaca');
    Route::post('/{id}/selesai', [DisposisiController::class, 'markDone'])->name('selesai');
});

/*
|--------------------------------------------------------------------------
| NOTIFIKASI (AJAX POLLING)
|--------------------------------------------------------------------------
*/
Route::get('/ajax/notifikasi-disposisi', function () {
    $karyawanId = 1; // DEV MODE

    $count = \App\Models\Disposisi::where('ke_karyawan_id', $karyawanId)
        ->where('status', 'baru')
        ->count();

    return response()->json([
        'count' => $count
    ]);
})->name('ajax.notifikasi.disposisi');

/*
|--------------------------------------------------------------------------
| AUDIT LOG
|--------------------------------------------------------------------------
*/
Route::get('/audit-log', [AuditLogController::class, 'index'])
    ->name('audit-log.index');
