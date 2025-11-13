<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
                            
Route::get('/', function () {
    return view('index'); // <-- Pastikan ini sesuai nama file: index.blade.php
});

// Atau jika Anda ingin akses via /dashboard
Route::get('/dashboard', function () {
    return view('index');
});

Route::get('/test', function () {
    return view('test');
});
Route::resource('surat-masuk', SuratMasukController::class);
