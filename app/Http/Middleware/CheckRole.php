<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect('/login');
        }
        
        $userRole = $request->user()->role;
        
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access.');
        }
        
        return $next($request);
    }
}