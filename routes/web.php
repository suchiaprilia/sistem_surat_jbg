<?php

use Illuminate\Support\Facades\Route;

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
