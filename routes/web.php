<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
use App\Http\Controllers\ProfileController;
use App\Models\Disposisi;
use App\Models\SuratMasuk;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| SEMUA FITUR (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::resource('divisi', DivisiController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('jenis-surat', JenisSuratController::class);

    Route::post('/karyawan/{id}/reset-password', [KaryawanController::class, 'resetPassword'])
        ->name('karyawan.resetPassword');

    /*
    |--------------------------------------------------------------------------
    | REKAP
    |--------------------------------------------------------------------------
    */
    Route::get('/rekap-surat', [RekapSuratController::class, 'index'])->name('rekap-surat');
    Route::get('/rekap-surat/export/pdf', [RekapSuratController::class, 'exportPdf'])
        ->name('rekap-surat.export.pdf');

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
   Route::prefix('disposisi')
    ->middleware('auth')
    ->name('disposisi.')
    ->group(function () {

        Route::get('/', [DisposisiController::class, 'index'])->name('index');

        Route::get('/{disposisi}/teruskan', [DisposisiController::class, 'forward'])->name('teruskan');
        Route::post('/{disposisi}/dibaca', [DisposisiController::class, 'markRead'])->name('dibaca');
        Route::post('/{disposisi}/selesai', [DisposisiController::class, 'markDone'])->name('selesai');

        // ✅ CREATE dari Surat Masuk: hanya ADMIN
        Route::middleware('role:admin')->group(function () {
            Route::get('/create/{suratMasuk}', [DisposisiController::class, 'create'])->name('create');
        });

        // ✅ STORE: boleh admin/pimpinan/staff (karena forward juga butuh store)
        Route::middleware('role:admin,pimpinan,staff')->group(function () {
            Route::post('/', [DisposisiController::class, 'store'])->name('store');
        });
    });



    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    /*
    |--------------------------------------------------------------------------
    | AUDIT LOG
    |--------------------------------------------------------------------------
    */
    Route::get('/audit-log', [AuditLogController::class, 'index'])
        ->name('audit-log.index');
});


/*
|--------------------------------------------------------------------------
| NOTIFIKASI (AJAX POLLING) - sementara pakai 1
|--------------------------------------------------------------------------
*/
Route::get('/ajax/notifikasi', function () {
    $user = auth()->user();
    $karyawan = $user?->karyawan;

    $disposisiBaru = 0;
    if ($karyawan) {
        $disposisiBaru = Disposisi::where('ke_karyawan_id', $karyawan->id_karyawan)
            ->where('status', 'baru')
            ->count();
    }

    // surat masuk baru (kalau kamu memang pakai)
    $suratMasukBaru = 0; // isi sesuai sistemmu kalau ada

    return response()->json([
        'surat_masuk' => $suratMasukBaru,
        'disposisi'   => $disposisiBaru,
        'total'       => $suratMasukBaru + $disposisiBaru,
    ]);
})->name('ajax.notifikasi');
