<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->displayLocale('pt_PT')
                ->locales(['ar','en','fr','pt_PT','es','ru']) // also accepts a closure
                ->renderHook('panels::global-search.before')
                ->flags([
                    'ar' => asset('flags/svg/sa.svg'),
                    'fr' => asset('flags/svg/fr.svg'),      
                    'en' => asset('flags/svg/us.svg'),
                    'pt_PT' => asset('flags/svg/pt.svg'),
                    'es' => asset('flags/svg/es.svg'),
                    'ru' => asset('flags/svg/ru.svg'),  
                ]);
        });
    }
}
