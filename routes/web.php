<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekapSuratController;
use App\Http\Controllers\SearchController;

// =======================
// HALAMAN DASHBOARD
// =======================
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.redirect');

// =======================
// HALAMAN REKAP SURAT
// =======================
Route::get('/rekap-surat', [RekapSuratController::class, 'index'])->name('rekap-surat');

Route::get('/rekap-surat/export/pdf', [RekapSuratController::class, 'exportPdf'])
    ->name('rekap-surat.export.pdf');

Route::get('/rekap-surat/export/excel', [RekapSuratController::class, 'exportExcel'])
    ->name('rekap-surat.export.excel');

// =======================
// HALAMAN LAIN
// =======================
Route::get('/test', function () {
    return view('test');
});

// =======================
// CRUD ROUTE RESOURCE
// =======================
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

// =======================
// PENCARIAN
// =======================
// Pencarian Surat
Route::get('/search', [SearchController::class, 'index'])->name('search');
