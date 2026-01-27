<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;

class NotifikasiController extends Controller
{
    public static function disposisiBaru()
    {
        // DEV MODE
        $karyawanId = 1;

        return Disposisi::where('ke_karyawan_id', $karyawanId)
            ->where('status', 'baru')
            ->count();
    }
}
