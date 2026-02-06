<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $karyawan = auth()->user()->karyawan;

        if (!$karyawan || !in_array($karyawan->role, $roles)) {
            abort(403, 'TIDAK PUNYA AKSES');
        }

        return $next($request);
    }
}
