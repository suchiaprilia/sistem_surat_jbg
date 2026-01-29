<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
Route::post(
    '/surat-masuk/{id}/read',
    [SuratMasukController::class, 'markAsRead']
)->name('surat-masuk.read');

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
Route::get('/ajax/notifikasi', function () {
    $karyawanId = 1; // nanti ganti auth()->id()

    $disposisiBaru = \App\Models\Disposisi::where('ke_karyawan_id', $karyawanId)
        ->where('status', 'baru')
        ->count();

    return response()->json([
        'total' => $disposisiBaru,
        'disposisi' => $disposisiBaru,
    ]);
})->name('ajax.notifikasi');








/*
|--------------------------------------------------------------------------
| AUDIT LOG
|--------------------------------------------------------------------------
*/
Route::get('/audit-log', [AuditLogController::class, 'index'])
    ->name('audit-log.index');
