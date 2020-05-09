<?php

namespace Jayenne\LaravelLocaleHero;

use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(
            [
            __DIR__.'/../config/locale-hero.php' => config_path('locale-hero.php'),
            ],
            'config'
        );

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app['router']->aliasMiddleware(
            'locale_hero',
            \Jayenne\LaravelLocaleHero\Middleware\LocaleHeroSession::class
        );

        $this->app['router']
            ->middleware(config('locale-hero.lang_switcher_middleware'))
            ->get(
                '/change_locale/{locale_code}',
                'Jayenne\LaravelLocaleHero\Controllers\LocaleSwitchController@switch'
            )
            ->name('locale_hero.switch');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
    }
}
