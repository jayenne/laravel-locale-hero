<?php

namespace Jayenne\LaravelLocaleHero;

use Carbon\Carbon;
use Jayenne\LaravelLocaleHero\Services\PreferedLanguage;

class LocaleHero
{
    /**
     * The locale_code from the session, or the fallback.
     *
     * @var string
     */
    protected $locale_code;

    /**
     * @array
     */
    protected $data;

    /**
     * LocaleHero constructor.
     */
    public function __construct()
    {
        $this->locale_code = session('locale_code', config('locale-hero.fallback'));
        $this->data = $this->getDataFromFile($this->locale_code);
    }

    public function overrideSession($locale_code)
    {
        // In case the override is not a 4 char value
        if (5 !== strlen($locale_code)) {
            $lang = new PreferedLanguage($locale_code);
            $locale_code = $lang->locale_code;
        }

        $this->locale_code = $locale_code;
        $this->data = $this->getDataFromFile($locale_code);
    }

    /**
     * It will return the right language code. This can be a two char representation (ex. "nl", dutch)
     * or a four char representation (ex. es_CO; Spanish-colombian).
     *
     * @return string
     */
    public function languageCode($override = false)
    {
        if ($override != false) {
            $this->overrideSession($override);
        }

        return $this->data->language_code;
    }

    /**
     * It will return the two character code representation of the country.
     *
     * @return string
     */
    public function countryCode()
    {
        return $this->data->country_code;
    }

    /**
     * It will return the name of the country represented by the country code.
     *
     * @return string
     */
    public function countryName()
    {
        return $this->data->country_name;
    }

    /**
     * It will return the two character code representation of the country.
     *
     * @return string
     */
    public function countryNameLocal()
    {
        return $this->data->country_name_local;
    }

    /**
     * It will return the name of the language in the internation/prefered/english language.
     * You can use this for nice country-selectors in your app.
     *
     * @return string
     */
    public function languageName()
    {
        return $this->data->language_name;
    }

    /**
     * It will return the name of the language in the lnaguage of the file.
     * You can use this for nice country-selectors in your app.
     *
     * @return string
     */
    public function languageNameLocal()
    {
        return $this->data->language_name_local;
    }
    /**
     * String representation of the dateformat with only numbers.
     * Ex: "Y-m-d".
     *
     * @return string
     */
    public function dateNumbersFormat()
    {
        return $this->data->date_numbers;
    }

    /**
     * String representation of the date with only numbers from the Carbon instance provided.
     * It will be translated through \Date
     * Ex: "2018-04-24".
     *
     * @param  Carbon $carbon
     * @return string
     */
    public function dateNumbers(Carbon $carbon, $override = false)
    {
        if ($override != false) {
            $this->overrideSession($override);
        }

        return $carbon->locale($this->data->language_code)->translatedFormat($this->data->date_numbers);
    }

    /**
     * String representation of the dateformat with only capitals, some javascript dateselectors use this.
     * Ex: "DD-MM-YYYY".
     *
     * @return string
     */
    public function dateNumbersFullCapitalsFormat()
    {
        return $this->data->date_numbers_full_capitals;
    }

    /**
     * String representation of the dateformat with words but without the day.
     * Ex: "F jS Y".
     *
     * @return string
     */
    public function dateWordsWithoutDayFormat()
    {
        return $this->data->date_words_without_day;
    }

    /**
     * String representation of the date in words but without the day.
     * It will be translated through \Date
     * Ex: "April 24th 2018".
     *
     * @param  Carbon $carbon
     * @return string
     */
    public function dateWordsWithoutDay(Carbon $carbon, $override = false)
    {
        if ($override != false) {
            $this->overrideSession($override);
        }

        return $carbon->locale($this->data->language_code)->translatedFormat($this->data->date_words_without_day);
    }

    /**
     * String representation of the dateformat with words but without the day.
     * Ex: "l F jS Y".
     *
     * @return string
     */
    public function dateWordsWithDayFormat()
    {
        return $this->data->date_words_with_day;
    }

    /**
     * String representation of the date with words but without the day.
     * It will be translated through \Date
     * Ex: "Tuesday April 24th 2018".
     *
     * @param  Carbon $carbon
     * @return string
     */
    public function dateWordsWithDay(Carbon $carbon, $override = false)
    {
        if ($override != false) {
            $this->overrideSession($override);
        }

        return $carbon->locale($this->data->language_code)->translatedFormat($this->data->date_words_with_day);
    }

    /**
     * String representation of the dateformat for a birthday.
     * Ex: "F jS".
     *
     * @return string
     */
    public function dateBirthdayFormat()
    {
        return $this->data->date_birthday;
    }

    /**
     * String representation of a birthday date.
     * It will be translated through \Date
     * Ex: "April 24th".
     *
     * @param  Carbon $carbon
     * @return string
     */
    public function dateBirthday(Carbon $carbon, $override = false)
    {
        if ($override != false) {
            $this->overrideSession($override);
        }

        return $carbon->locale($this->data->language_code)->translatedFormat($this->data->date_birthday);
    }

    /**
     * String representation of the timeformat.
     * Ex: "h:i a".
     *
     * @return string
     */
    public function timeFormat()
    {
        return $this->data->time_format;
    }

    /**
     * String representation of time.
     * It will be translated through \Date
     * Ex: "12:00 pm".
     *
     * @param  Carbon $carbon
     * @return string
     */
    public function time(Carbon $carbon, $override = false)
    {
        if ($override != false) {
            $this->overrideSession($override);
        }

        return $carbon->locale($this->data->language_code)->translatedFormat($this->data->time_format);
    }

    /**
     * Emoji representation of language country flag.
     * Ex: "🇱🇹".
     *
     * @return string
     */
    public function emojiFlag()
    {
        return $this->data->emoji_flag;
    }

    public function allLanguages()
    {
        return collect(config('locale-hero.allowed'))
            ->map(
                function ($item) {
                    $file = $this->getDataFromFile($item);

                    return [
                    'locale_code' => $item, // depreciated
                    'country_code' => $file->country_code,
                    'country_name' => $file->country_name,
                    'country_name_local' => $file->country_name_local,
                    'language_code' => $file->language_code,
                    'language_name' => $file->language_name,
                    'language_name_local' => $file->language_name_local,
                    'emoji_flag' => $file->emoji_flag,
                    'currency_code' => $file->currency_code,
                    'currency_name' => $file->currency_name,
                    'currency_name_local' => $file->currency_name_local,
                    ];
                }
            );
    }

    /**
     * It will return the iso code of the country's currency.
     *
     * @return string
     */
    public function currencyCode()
    {
        return $this->data->currency_code;
    }

    /**
     * It will return the name of the country's currency.
     *
     * @return string
     */
    public function currencyName()
    {
        return $this->data->currency_name;
    }

    /**
     * It will return the name of the country's currency as spoken locally by language code.
     *
     * @return string
     */
    public function currencyNameLocal()
    {
        return $this->data->currency_name_local;
    }

    /**
     * It will return the name of the country's currency as spoken locally by language code.
     *
     * @return string
     */
    public function currencyFormatted(float $amount, $currency_code = null)
    {
        if (class_exists('NumberFormatter')) {
            $locale_code = session('locale_code');
            $currency_code = is_null($currency_code) ? $this->data->currency_code : $currency_code;

            $formatter = new \NumberFormatter($locale_code, \NumberFormatter::CURRENCY);
            return $formatter->formatCurrency($amount, $currency_code);
        } else {
            return 'class NumberFormatter is required. Please install php.intl';
        }
    }
    
    /**
     * It will return the symbol of the country's currency for the language code.
     *
     * @return string
     */
    public function currencySymbol($currency_code = null)
    {
        $locale_code = session('locale_code');
        $currency_code = is_null($currency_code) ? $this->data->currency_code : $currency_code;

        if (class_exists('NumberFormatter')) {
            $formatter = new \NumberFormatter($locale_code . '@currency=' . $currency_code, \NumberFormatter::CURRENCY);
            return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
        }
        return false;
    }

    /**
     * It will return a collection with the current language, country and name
     * and also the other available language, country and name.
     */
    public function langSelectorHelper()
    {
        return $this->allLanguages()->reduce(
            function ($carry, $item) {
                if ($item['locale_code'] != session('locale_code')) {
                    $carry['available'][] = $item;
                } else {
                    $carry['current'] = $item;
                }

                return $carry;
            }
        );
    }

    public function setAllSessions($prefered_lang)
    {
        $lang = new PreferedLanguage($prefered_lang);
        session(['locale_code' => $lang->locale_code]);
        session(['locale' => $lang->locale]);
    }

    /**
     * Retreive the data for the locale_code from the external file.
     *
     * @param  $locale_code
     * @return mixed
     */
    private function getDataFromFile($locale_code)
    {
        if ($locale_code === null) {
            return[];
        }

        if (file_exists(resource_path('lang/locale-overrides/'.$locale_code.'.json'))) {
            $resource = resource_path('lang/locale-overrides/'.$locale_code.'.json');
        } else {
            $resource = __DIR__.'/LocaleHeroData/'.$locale_code.'.json';
        }

        return json_decode(file_get_contents($resource));
    }
}
