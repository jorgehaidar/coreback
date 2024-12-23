<?php

use App\Http\Controllers\Security\AuthController;
use App\Http\Controllers\Security\RateLimitBlockController;
use App\Http\Controllers\Security\RouteController;
use App\Http\Controllers\Security\UserController;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\LogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'api ready';
});

Route::group([

    'middleware' => ['api', 'auth.control'],
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth.control']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('permissions', [AuthController::class, 'getPermissions']);
    Route::post('change-password', [AuthController::class, 'changePassword']);

    //Recovery password
    Route::post('restore-password', [AuthController::class, 'sendRecoveryEmail'])->withoutMiddleware('auth.control');
    Route::post('validate-code', [AuthController::class, 'validateCode'])->withoutMiddleware('auth.control');
    Route::post('restore-password/{hash}', [AuthController::class, 'reset'])->withoutMiddleware('auth.control');
});

Route::middleware([
    'api',
    'throttle:api',
    'auth.control',
    'access.control'
])->group(function () {
    Route::get('/rate-limits', [RateLimitBlockController::class, 'index']);
    Route::delete('/rate-limits/{key}', [RateLimitBlockController::class, 'destroy']);

    Route::resource('roles', RoleController::class);

    Route::resource('users', UserController::class);

    Route::resource('logs', LogController::class)->only('index');

    Route::resource('routes', RouteController::class)->only('index', 'update');

});
