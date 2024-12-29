<?php

namespace App\Providers;

use App\Exceptions\Handler;
use App\Models\Security\RateLimitBlock;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (env('LOG_ERROR')) {
            $this->app->singleton(
                ExceptionHandler::class,
                Handler::class
            );
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        RateLimiter::for('api', function (Request $request) {
            $enableProgressiveLimiter = env('ENABLE_PROGRESSIVE_RATE_LIMITER', true);

            if ($enableProgressiveLimiter) {
                $key = $request->ip();
                $block = RateLimitBlock::where('key', $key)->first();

                if ($block && $block->active && $block->blocked_until && now()->lessThan($block->blocked_until)) {
                    $remaining = max(0, now()->diffInMinutes($block->blocked_until));
                    return response()->json([
                        'success' => false,
                        'message' => "You are blocked. Try again in {$remaining} minutes.",
                    ], 429);
                }

                $timeouts = [1, 60, 720, 1440, -1]; // Minutos: 1min, 1hr, 12hr, 24hr, Permanente
                $level = $block->level ?? 0;
                $timeout = $timeouts[$level] ?? -1;

                if ($timeout === -1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are permanently blocked. Contact an administrator.',
                    ], 429);
                }

                return Limit::perMinute(60)->by($key)->response(function () use ($key, $block, $level, $timeout) {
                    $nextLevel = $level + 1;
                    $blockedUntil = now()->addMinutes($timeout);

                    if ($block) {
                        $block->update(['level' => $nextLevel, 'blocked_until' => $blockedUntil, 'active' => true]);
                    } else {
                        RateLimitBlock::create(['key' => $key, 'level' => $nextLevel, 'blocked_until' => $blockedUntil]);
                    }

                    return response()->json([
                        'success' => false,
                        'message' => "Too many requests. You are blocked for {$timeout} minutes.",
                    ], 429);
                });
            }

            return [
                Limit::perSecond(20)->by($request->ip()),
                Limit::perMinute(240)->by($request->ip()),
            ];
        });


        $log = env('LOG_QUERY', false);
        if ($log)
            DB::listen(function (QueryExecuted $query) {
                File::append(
                    storage_path('/logs/query.log'),
                    $query->sql . ' [' . implode(', ', $query->bindings) . ']' . '[' . $query->time . ']' . PHP_EOL
                );
            });
    }
}
