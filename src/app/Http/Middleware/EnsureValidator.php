<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureValidator
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check() || ! auth()->user()->hasAnyRole(['validator', 'super_admin', 'admin'])) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
