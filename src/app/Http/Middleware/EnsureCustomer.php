<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCustomer
{
    public function handle(Request $request, Closure $next)
    {
        if (! auth()->check() || ! auth()->user()->hasRole('customer')) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
