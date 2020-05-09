<?php

namespace Jayenne\LaravelLocaleHero\Tests\Unit;

use Jayenne\LaravelLocaleHero\Services\PreferedLanguage;
use Jayenne\LaravelLocaleHero\Tests\TestCase;

class LocaleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set config variables
        $this->app['config']->set('locale-hero.fallback', 'en-GB');
        $this->app['config']->set('locale-hero.allowed', [
            'nl-NL',
            'nl-BE',
            'en-GB',
            'en-US',
            'en-CA',
            'en-AU',
            'es-CO',
        ]);
    }

    /**
     * @group locale_test
     * @test
     */
    public function four_char_json_available()
    {
        $file = __DIR__.'/../Support/Files/es-CO.json';
        $dest = resource_path('/lang/').'es-CO.json';
        copy($file, $dest);

        $lang = new PreferedLanguage('es-CO,en');

        $this->assertEquals('es-CO', $lang->locale);

        @unlink($dest);
    }

    /**
     * @group locale_test
     * @test
     */
    public function four_char_json_not_available()
    {
        $lang = new PreferedLanguage('es-CO,en');

        $this->assertEquals('es', $lang->locale);
    }
}
