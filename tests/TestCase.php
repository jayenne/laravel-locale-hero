<?php

namespace Jayenne\LaravelLocaleHero\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->artisan('migrate');
    }

    protected function getPackageProviders($app)
    {
        return [
            \Jayenne\LaravelLocaleHero\LocaleServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'LocaleHero' => \Jayenne\LaravelLocaleHero\LocaleHeroFacade::class,
            'Auth' => \Illuminate\Support\Facades\Auth::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('app.key', 'base64:f9kzW7cVoE96+f+00BKmlvFujZGy5Pf5GHG6/51mbns=');
        $app['config']->set('locale-hero.lang_switcher_middleware', ['web', 'locale_hero']);

        $app['config']->set('app.debug', true);

        // Set the test routes
        $app['router']
            ->middleware(['web', 'locale_hero'])
            ->get('test_route', 'Jayenne\LaravelLocaleHero\Tests\Support\Controllers\TestRoutesController@testRoute')
            ->name('test_route');
    }
}
