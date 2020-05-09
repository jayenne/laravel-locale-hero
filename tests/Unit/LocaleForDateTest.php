<?php

namespace Jayenne\LaravelLocaleHero\Tests\Unit;

use Jayenne\LaravelLocaleHero\Services\PreferedLanguage;
use Jayenne\LaravelLocaleHero\Tests\TestCase;

class LocaleForDateTest extends TestCase
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
            'de-DE',
            'zh-CN',
        ]);
    }

    /**
     * @group locale_for_date_test
     * @test
     */
    public function no_four_char_json_available_in_date_package_fallback_to_just_lang()
    {
        $lang = new PreferedLanguage('nl-NL');

        $this->assertEquals('nl', $lang->locale);
    }

    /**
     * @group locale_for_date_test
     * @test
     */
    public function no_match_fallback_to_date_package_fallback()
    {
        $lang = new PreferedLanguage('xx-XX');

        $this->assertEquals('en', $lang->locale);
    }
}
