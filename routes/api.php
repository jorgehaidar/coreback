<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\security\RateLimitBlockController;
use App\Http\Controllers\security\UserController;
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

Route::middleware(['access.control', 'auth.control'])->group(function () {
    Route::get('/rate-limits', [RateLimitBlockController::class, 'index']);
    Route::delete('/rate-limits/{key}', [RateLimitBlockController::class, 'destroy']);
});



Route::middleware(['api', 'throttle:api'])->group(function () {
    Route::resource('user', UserController::class);
});
