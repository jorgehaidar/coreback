<?php

use App\Http\Controllers\Security\AuthController;
use App\Http\Controllers\Security\RateLimitBlockController;
use App\Http\Controllers\Security\UserController;
use App\Http\Controllers\Security\RoleController;
use App\Http\Controllers\Security\LogController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'api ready';
});

Route::group([

    'middleware' => ['api'],
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::middleware(['auth.control', 'access.control'])->group(function () {
    Route::get('/rate-limits', [RateLimitBlockController::class, 'index']);
    Route::delete('/rate-limits/{key}', [RateLimitBlockController::class, 'destroy']);
    Route::resource('roles', RoleController::class);
    Route::post('test', [UserController::class, 'test']);

});

Route::middleware(['api', 'throttle:api'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('logs', LogController::class);
});
