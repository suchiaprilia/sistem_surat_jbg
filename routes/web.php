<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DivisiController;

Route::get('/', function () {
    return view('index');
});

Route::get('/dashboard', function () {
    return view('index');
})->name('dashboard');


Route::get('/test', function () {
    return view('test');
});

Route::resource('surat-masuk', SuratMasukController::class);

Route::resource('surat-keluar', SuratKeluarController::class);

Route::resource('divisi', DivisiController::class);