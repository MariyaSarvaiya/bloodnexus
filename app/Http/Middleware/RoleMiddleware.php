<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $role
    ): Response {

        if (!auth()->check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        $user = auth()->user();

        if ($user->role !== $role) {

            if ($user->role === 'user') {
                return redirect()
                    ->route('dashboard')
                    ->with(
                        'error',
                        'You do not have permission to access this page.'
                    );
            }

            if ($user->role === 'donor') {
                return redirect()
                    ->route('donor.dashboard')
                    ->with(
                        'error',
                        'You do not have permission to access this page.'
                    );
            }

            if ($user->role === 'admin') {
                return redirect()
                    ->route('admin.dashboard')
                    ->with(
                        'error',
                        'You do not have permission to access this page.'
                    );
            }

            abort(403);
        }

        return $next($request);
    }
}