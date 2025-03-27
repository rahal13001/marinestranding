<?php

namespace App\Providers;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use BezhanSalleh\PanelSwitch\PanelSwitch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $panelSwitch
                ->modalHeading('Switch Aplikasi')
                ->labels([
                    'biotalaut' => 'Biota Laut Terdampar',
                    'kkprl' => 'Peta KKPRL'
                ])
                ->icons([
                    'biotalaut' => 'phosphor-fish-simple-fill',
                    'kkprl' => 'heroicon-s-globe-asia-australia',
                ], $asImage = false)
                ->slideOver()
                ->modalWidth('sm')
                ->simple();
        });

        // Register the 'public-api' rate limiter
        RateLimiter::for('public-api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
