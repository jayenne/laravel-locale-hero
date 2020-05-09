<?php

namespace Jayenne\LaravelLocaleHero\Tests\Unit;

use App;
use Carbon\Carbon;
use Jayenne\LaravelLocaleHero\Tests\TestCase;

class LocaleHeroTest extends TestCase
{
    protected $test_date;

    protected function setUp(): void
    {
        parent::setUp();

        $this->test_date = Carbon::create(2018, 03, 10, 13, 05);

        // Set config variables
        $this->app['config']->set('locale-hero.fallback', 'en-GB');
        $this->app['config']->set('locale-hero.allowed', [
        'nl-NL',
        'nl-BE',
        'en-GB',
        'en-US',
        ]);
    }

    /**
     * @group locale_code_test
     * @test
     */
    public function it_returns_the_right_values_for_nl_NL()
    {
        session(['locale_code' => 'nl-NL']);
        App::setLocale('nl');

        $this->assertEquals('NL', \LocaleHero::countryCode());
        $this->assertEquals('The Netherlands', \LocaleHero::countryName());
        $this->assertEquals('Nederland', \LocaleHero::countryNameLocal());
        $this->assertEquals('nl', \LocaleHero::languageCode());
        $this->assertEquals('Dutch', \LocaleHero::languageName());
        $this->assertEquals('Vlaams', \LocaleHero::languageNameLocal());
        $this->assertEquals('d-m-Y', \LocaleHero::dateNumbersFormat());
        $this->assertEquals('10-03-2018', \LocaleHero::dateNumbers($this->test_date));
        $this->assertEquals('DD-MM-YYYY', \LocaleHero::dateNumbersFullCapitalsFormat());
        $this->assertEquals('j F Y', \LocaleHero::dateWordsWithoutDayFormat());
        $this->assertEquals('10 maart 2018', \LocaleHero::dateWordsWithoutDay($this->test_date));
        $this->assertEquals('l j F Y', \LocaleHero::dateWordsWithDayFormat());
        $this->assertEquals('zaterdag 10 maart 2018', \LocaleHero::dateWordsWithDay($this->test_date));
        $this->assertEquals('j F', \LocaleHero::dateBirthdayFormat());
        $this->assertEquals('10 maart', \LocaleHero::dateBirthday($this->test_date));
        $this->assertEquals('H:i', \LocaleHero::timeFormat());
        $this->assertEquals('13:05', \LocaleHero::time($this->test_date));
        $this->assertEquals('🇳🇱', \LocaleHero::emojiFlag());
        $this->assertEquals('EUR', \LocaleHero::currencyCode());
        $this->assertEquals('€', \LocaleHero::currencySymbol());
        $this->assertEquals('US$', \LocaleHero::currencySymbol("USD"));
        $this->assertEquals('Euro', \LocaleHero::currencyName());
        $this->assertEquals('Euro', \LocaleHero::currencyNameLocal());

        $expected = [
        'current' => [
            'country_code' => 'NL',
            'country_name' => 'The Netherlands',
            'country_name_local' => 'Nederland',
            'language_code' => 'nl',
            'language_name' => 'Dutch',
            'language_name_local' => 'Vlaams',
            'locale_code' => 'nl-NL',
            'emoji_flag' => '🇳🇱',
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'currency_name_local' => 'Euro'
        ],
        'available' => [
            [
                'country_code' => 'BE',
                'country_name' => 'Belgium',
                'country_name_local' => 'België',
                'language_code' => 'nl',
                'language_name' => 'Belgian Dutch',
                'language_name_local' => 'België - Vlaams',
                'locale_code' => 'nl-BE',
                'emoji_flag' => '🇧🇪',
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'currency_name_local' => 'Euro'
            ],
            [
                'country_code' => 'GB',
                'country_name' => 'United Kingdom',
                'country_name_local' => 'United Kingdom',
                'language_code' => 'en',
                'language_name' => 'English',
                'language_name_local' => 'English',
                'locale_code' => 'en-GB',
                'emoji_flag' => '🇬🇧',
                'currency_code' => 'GBP',
                'currency_name' =>'Pound Stirling',
                'currency_name_local' => 'Pound'
            ],
            [
                'country_code' => 'US',
                'country_name' => 'United States of America',
                'country_name_local' => 'America',
                'language_code' => 'en',
                'language_name' => 'English',
                'language_name_local' => 'American English',
                'locale_code' => 'en-US',
                'emoji_flag' => '🇺🇸',
                'currency_code' => 'USD',
                'currency_name' => 'Dollar',
                'currency_name_local' => 'US Dollar'
            ],
        ],
        ];
        $this->assertEquals($expected, \LocaleHero::langSelectorHelper());
    }

    /**
     * @group locale_code_test
     * @test
     */
    public function it_returns_the_right_values_for_en_US()
    {
        session(['locale_code' => 'en-US']);
        App::setLocale('en');

        $this->assertEquals('US', \LocaleHero::countryCode());
        $this->assertEquals('United States of America', \LocaleHero::countryName());
        $this->assertEquals('America', \LocaleHero::countryNameLocal());
        $this->assertEquals('en', \LocaleHero::languageCode());
        $this->assertEquals('English', \LocaleHero::languageName());
        $this->assertEquals('American English', \LocaleHero::languageNameLocal());
        $this->assertEquals('m/d/Y', \LocaleHero::dateNumbersFormat());
        $this->assertEquals('03/10/2018', \LocaleHero::dateNumbers($this->test_date));
        $this->assertEquals('MM/DD/YYYY', \LocaleHero::dateNumbersFullCapitalsFormat());
        $this->assertEquals('F jS Y', \LocaleHero::dateWordsWithoutDayFormat());
        $this->assertEquals('March 10th 2018', \LocaleHero::dateWordsWithoutDay($this->test_date));
        $this->assertEquals('l F jS Y', \LocaleHero::dateWordsWithDayFormat());
        $this->assertEquals('Saturday March 10th 2018', \LocaleHero::dateWordsWithDay($this->test_date));
        $this->assertEquals('F jS', \LocaleHero::dateBirthdayFormat());
        $this->assertEquals('March 10th', \LocaleHero::dateBirthday($this->test_date));
        $this->assertEquals('h:i a', \LocaleHero::timeFormat());
        $this->assertEquals('01:05 pm', \LocaleHero::time($this->test_date));
        $this->assertEquals('🇺🇸', \LocaleHero::emojiFlag());
        $this->assertEquals('USD', \LocaleHero::currencyCode());
        $this->assertEquals('Dollar', \LocaleHero::currencyName());
        $this->assertEquals('US Dollar', \LocaleHero::currencyNameLocal());

        $expected = [
        'current' => [
            'country_code' => 'US',
            'country_name' => 'United States of America',
            'country_name_local' => 'America',
            'language_code' => 'en',
            'language_name' => 'English',
            'language_name_local' => 'American English',
            'locale_code' => 'en-US',
            'emoji_flag' => '🇺🇸',
            'currency_code' => 'USD',
            'currency_name' => 'Dollar',
            'currency_name_local' => 'US Dollar',
        ],
        'available' => [
            [
                'country_code' => 'NL',
                'country_name' => 'The Netherlands',
                'country_name_local' => 'Nederland',
                'language_code' => 'nl',
                'language_name' => 'Dutch',
                'language_name_local' => 'Vlaams',
                'locale_code' => 'nl-NL',
                'emoji_flag' => '🇳🇱',
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'currency_name_local' => 'Euro',
            ],
            [
                'country_code' => 'BE',
                'country_name' => 'Belgium',
                'country_name_local' => 'België',
                'language_code' => 'nl',
                'language_name' => 'Belgian Dutch',
                'language_name_local' => 'België - Vlaams',
                'locale_code' => 'nl-BE',
                'emoji_flag' => '🇧🇪',
                'currency_code' => 'EUR',
                'currency_name' => 'Euro',
                'currency_name_local' => 'Euro',
            ],
            [
                'country_code' => 'GB',
                'country_name' => 'United Kingdom',
                'country_name_local' => 'United Kingdom',
                'language_code' => 'en',
                'language_name' => 'English',
                'language_name_local' => 'English',
                'locale_code' => 'en-GB',
                'emoji_flag' => '🇬🇧',
                'currency_code' => 'GBP',
                'currency_name' => 'Pound Stirling',
                'currency_name_local' => 'Pound',
            ],
        ],
        ];
        $this->assertEquals($expected, \LocaleHero::langSelectorHelper());
    }

    /**
     * @group locale_code_test
     * @test
     */
    public function it_returns_the_right_values_for_en_US_while_session_is_nl_NL_but_is_overruled()
    {
        session(['locale_code' => 'nl-NL']);
        App::setLocale('nl');

        $this->assertEquals('03/10/2018', \LocaleHero::dateNumbers($this->test_date, 'en-US'));
        $this->assertEquals('March 10th 2018', \LocaleHero::dateWordsWithoutDay($this->test_date, 'en-US'));
        $this->assertEquals('Saturday March 10th 2018', \LocaleHero::dateWordsWithDay($this->test_date, 'en-US'));
        $this->assertEquals('March 10th', \LocaleHero::dateBirthday($this->test_date, 'en-US'));
        $this->assertEquals('01:05 pm', \LocaleHero::time($this->test_date, 'en-US'));
    }

    /**
     * @group locale_code_test
     * @test
     */
    public function it_returns_all_the_availeble_languages()
    {
        $expected = [
        [
            'country_code' => 'NL',
            'country_name' => 'The Netherlands',
            'country_name_local' => 'Nederland',
            'language_code' => 'nl',
            'language_name' => 'Dutch',
            'language_name_local' => 'Vlaams',
            'locale_code' => 'nl-NL',
            'emoji_flag' => '🇳🇱',
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'currency_name_local' => 'Euro'
        ],
        [
            'country_code' => 'BE',
            'country_name' => 'Belgium',
            'country_name_local' => 'België',
            'language_code' => 'nl',
            'language_name' => 'Belgian Dutch',
            'language_name_local' => 'België - Vlaams',
            'locale_code' => 'nl-BE',
            'emoji_flag' => '🇧🇪',
            'currency_code' => 'EUR',
            'currency_name' => 'Euro',
            'currency_name_local' => 'Euro'
        ],
        [
            'country_code' => 'GB',
            'country_name' => 'United Kingdom',
            'country_name_local' => 'United Kingdom',
            'language_code' => 'en',
            'language_name' => 'English',
            'language_name_local' => 'English',
            'locale_code' => 'en-GB',
            'emoji_flag' => '🇬🇧',
            'currency_code' => 'GBP',
            'currency_name' => 'Pound Stirling',
            'currency_name_local' => 'Pound'
        ],
        [
            'country_code' => 'US',
            'country_name' => 'United States of America',
            'country_name_local' => 'America',
            'language_code' => 'en',
            'language_name' => 'English',
            'language_name_local' => 'American English',
            'locale_code' => 'en-US',
            'emoji_flag' => '🇺🇸',
            'currency_code' => 'USD',
            'currency_name' => 'Dollar',
            'currency_name_local' => 'US Dollar'
        ],
        ];
        $this->assertEquals(collect($expected), \LocaleHero::allLanguages());
    }

    /** @test */
    public function it_uses_the_override_when_available()
    {
        session(['locale_code' => 'nl-NL']);
        App::setLocale('nl');

        $file = __DIR__.'/../Support/Files/locale-overrides/nl-NL.json';
        $dir = resource_path('lang/locale-overrides/');

        if (!is_dir($dir)) {
            mkdir($dir);
        }
        if (!is_file($dir)) {
            $dest = $dir.'nl-NL.json';
        }

        copy($file, $dest);

        $this->assertEquals('NL', \LocaleHero::countryCode());
        $this->assertEquals('nl', \LocaleHero::languageCode());
        $this->assertEquals('Dutch (override test)', \LocaleHero::languageName());
        $this->assertEquals('Vlaams (override test)', \LocaleHero::languageNameLocal());

        // Remove test files from testbench
        unlink(resource_path('lang/locale-overrides/').'nl-NL.json');
        rmdir(resource_path('lang/locale-overrides/'));
    }

    /**
     * @test
     */
    public function get_the_language_for_an_overrided_locale_code()
    {
        session(['locale_code' => 'nl-NL']);
        App::setLocale('nl');

        $this->assertEquals('en', \LocaleHero::languageCode('en-US'));
    }

    /**
     * @test
     */
    public function it_returns_the_given_amount_formatted_to_en_GB_locale()
    {
        session(['locale_code' => 'en-GB']);
        $amount = '12345678.90';
        $this->assertEquals('£12,345,678.90', \LocaleHero::currencyFormatted($amount));
    }
    /**
     * @test
     */
    public function it_returns_the_given_amount_formatted_to_en_CA_locale()
    {
        session(['locale_code' => 'en-CA']);
        $amount = '12345678.90';
        $this->assertEquals('$12,345,678.90', \LocaleHero::currencyFormatted($amount));
    }

    /**
     * @test
     */
    public function it_returns_the_given_amount_formatted_to_fr_CA_locale()
    {
        session(['locale_code' => 'fr-CA']);
        $amount = '12345678.90';
        $this->assertEquals('12 345 678,90 $', \LocaleHero::currencyFormatted($amount));
    }

    /**
     * @test
     */
    public function it_returns_the_currency_symbol_for_en_GB_locale()
    {
        session(['locale_code' => 'en-GB']);
        $this->assertEquals('£', \LocaleHero::currencySymbol());
    }

    /**
     * @test
     */
    public function it_returns_the_currency_symbol_for_fr_CH_locale()
    {
        session(['locale_code' => 'fr-CH']);
        $this->assertEquals('CHF', \LocaleHero::currencySymbol());
    }

    /**
     * @test
     */
    public function it_returns_the_currency_symbol_international_for_en_CA_locale()
    {
        session(['locale_code' => 'es-ES']);
        $this->assertEquals('US$', \LocaleHero::currencySymbol("USD"));
    }
}
