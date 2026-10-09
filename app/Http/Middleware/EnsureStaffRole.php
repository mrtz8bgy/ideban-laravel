<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureStaffRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (!$user || ($user->role !== 'admin' && !in_array($user->role, $roles, true))) {
            abort(403);
        }

        return $next($request);
    }
}
