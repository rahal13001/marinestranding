<?php

namespace App\Providers;

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
    }
}
