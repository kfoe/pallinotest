<?php

namespace App\Providers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        $this->customShopModelBindings();

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->as('api.')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    private function customShopModelBindings(): void
    {
        Route::bind('shopIdOrShopCountryCode', function ($shopIdOrShopCountryCode) {
            return Shop::whereExternalId($shopIdOrShopCountryCode)->first() ?? Shop::whereCountry($shopIdOrShopCountryCode)->firstOrFail();
        });
    }
}
