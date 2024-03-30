<?php
/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dash';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // old value is 60
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(140)->by($request->user()?->id ?: $request->ip());
        });

        /*RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(200)->by($request->user()?->id ?: $request->ip());
        }); // TODO: think*/

        // rate limiter for payment creation
        RateLimiter::for('pay', function (Request $request) {
            return Limit::perMinutes(30, 2)->by($request->user()?->id ?: $request->ip())->response(function (Request $request) {
                return back()->withErrors(['requests' => __('errors.payments.rate_limit_reached')]);
            });
        });

        $this->routes(function () {
            Route::middleware('web')
                ->prefix('auth')
                ->group(base_path('routes/fortify.php'));

            Route::middleware('web')
                ->prefix('dash')
                ->group(base_path('routes/jetstream.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->prefix('articles')
                ->group(base_path('routes/articles.php'));
        });
    }
}
