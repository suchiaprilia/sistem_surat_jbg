<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuratMasukController;
                            
Route::get('/', function () {
    return view('index'); // Ini akan mencari file resources/views/index.blade.php
});
Route::resource('surat-masuk', SuratMasukController::class);
