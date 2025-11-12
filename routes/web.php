<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index'); // Ini akan mencari file resources/views/index.blade.php
});
