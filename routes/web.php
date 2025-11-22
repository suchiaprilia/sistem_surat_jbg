<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;

// =======================
// HALAMAN DASHBOARD
// =======================
Route::get('/', function () {
    return view('index');
})->name('dashboard');   // <-- ini nama route dashboard utamanya

Route::get('/dashboard', function () {
    return view('index');
})->name('dashboard');   // <-- biar / dan /dashboard sama-sama dashboard


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

