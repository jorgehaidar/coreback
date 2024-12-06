<?php

use App\Http\Middleware\AccessControl;
use App\Http\Middleware\AuthControl;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->throttleApi();
        $middleware->convertEmptyStringsToNull();
        $middleware->encryptCookies();
        $middleware->trustProxies();
        $middleware->trimStrings();
        $middleware->validateCsrfTokens();
        $middleware->append(SecurityHeaders::class);
        $middleware->alias([
            'access.control' => AccessControl::class,
            'auth.control' => AuthControl::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
