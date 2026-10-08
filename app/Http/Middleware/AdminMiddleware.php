<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // BloodNexus fixed administrator. This session is intentionally
        // independent of the users table, so changing rows in phpMyAdmin
        // cannot change the fixed admin credentials.
        if ($request->session()->get('fixed_admin') === true) {
            return $next($request);
        }

        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
