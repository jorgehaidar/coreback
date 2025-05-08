<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Mbox\BackCore\Middleware\AccessControl;
use Mbox\BackCore\Middleware\ApiKeyMiddleware;
use Mbox\BackCore\Middleware\AuthControl;
use Mbox\BackCore\Middleware\EnsureEmailIsVerified;
use Mbox\BackCore\Middleware\SecurityHeaders;

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
            'auth.control' => AuthControl::class,
            'verified' => EnsureEmailIsVerified::class,
            'api.key' => ApiKeyMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
