<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\UpgradeToHttpsUnderNgrok; // Ensure the middleware is imported

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            // Add your middleware here
            UpgradeToHttpsUnderNgrok::class,
        ]);
        // You can also configure other middleware stacks if needed
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
