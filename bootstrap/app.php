<?php

// Ensure Laravel's file-session/cache directories exist after ZIP extraction on Windows/XAMPP.
foreach ([
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/cache',
    __DIR__ . '/../storage/framework/cache/data',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/cache',
] as $directory) {
    if (!is_dir($directory)) {
        @mkdir($directory, 0777, true);
    }
}

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityActivityMiddleware;

return Application::configure(
    basePath: dirname(__DIR__)
)
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'role' => RoleMiddleware::class,
            'security.activity' => SecurityActivityMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();