# LaravelLocaleHero

# Important!
This package is Laravel 7+!

## TL;DR
Setting the locale is not enough for most needs, many countries have more than one official language, different date formats, number notations and currencies. This package is here to help you with all of that!
In a nutshell:
* You can set the all supported locale's your choice within the config file.
* It will make smart fallbacks where possible.
* You can now show dates not only translated, but also in the right format! (example: jan 1st 2020; 1st jan 2020. Both the same date, both translated, but every country has it's own spcific order).
* It will help you to show the right flag in your language switcher (because you now also have a country value, not only a language value).
* It will help you to show the right currency in your language switcher (because you now also have a currency value, not only a language value).
* We'll assume you're using Translation Strings As Keys ([Using Translation Strings As Keys](https://laravel.com/docs/7.x/localization#using-translation-strings-as-keys))


## Install

You can install this package via composer using this command:

``` bash
$ composer require jayenne/laravel-locale-hero
```
The package will automatically register itself.

You can publish the config-file with:

``` bash
php artisan vendor:publish --provider="Jayenne\LaravelLocaleHero\LocaleServiceProvider" --tag="config"
```

Set the middleware. Add this in your `app\Http\Kernel.php` file to the $middlewareGroups web property:

``` php
protected $middlewareGroups = [
    'web' => [
        ....
        'locale_hero'
    ],
```

As soon as you run the migration, a `locale_code` column will be set for your User table. The migration will be loaded through the service provider of the package, so you don't need to publish it first.

``` php
php artisan migrate
```
Make sure `locale_code` on your `User` model is added to the $fillable property. Example:

``` php
protected $fillable = [
    'firstname', 'lastname', 'email', 'password', 'locale_code'
];
```

To make sure this will be loaded and stored in the session add this to method to your `app\Http\Controllers\Auth\LoginController.php`:

```
public function authenticated(Request $request, $user)
{
    // Set the right sessions
    if (null != $user->locale_code) {
        \LocaleHero::setAllSessions($user->locale_code);
    }

    return redirect()->intended($this->redirectPath());
}
```

**That's all folks!**

## What will it do?
For each user and guest it will create a `locale_code` code. For guests it will try to make a perfect match based on the browser settings and for users, it will load the last set `locale_code` from the database.

**There will ALWAYS be two sessions set:**

- `locale_code` This is the full locale, consisting of both language and country codes. (example: `nl-BE`)
- `locale` This is the language code only (examples: `nl`, `es-CO`)

When a user will log in to your app, it will load the last `locale_code` and set the sessions accordingly.

## How to switch lang_country
There is a named route you can use that takes the new lang_country as a parameter:

``` php
route('locale_hero.switch', ['locale_code' => 'nl-BE'])
```

It will first check if the requested `locale_code` is in your `allowed` list of your config file. When so, it will change all the sessions accordingly. When it detects there is a logged in User, it will also set the `locale_code` to the database.

It's really easy to create a language selector! You can use `LocaleHero::langSelectorHelper()`.
This will output for example:

``` php
Array
(
    [available] => Array
        (
            [0] => Array
                (
                    [country] => NL
                    [country_name] => The Netherlands
                    [country_name_local] => Nederlands
                    [lang] => nl
                    [name] => Nederlands
                    [lang_country] => nl-NL
                    [emoji_flag] => 🇳🇱
                    [currency_code] => EUR
                    [currency_name] => Euro
                    [currency_name_local] => Euro
                )

            [1] => Array
                (
                    [country] => BE
                    [country_name] => The Netherlands
                    [country_name_local] => Nederlands
                    [lang] => nl
                    [name] => België - Vlaams
                    [lang_country] => nl-BE
                    [emoji_flag] => 🇧🇪
                    [currency_code] => EUR
                    [currency_name] => Euro
                    [currency_name_local] => Euro
                )

            [2] => Array
                (
                    [country] => GB
                    [country_name] => United Kingdom
                    [country_name_local] => United Kingdom
                    [lang] => en
                    [name] => English
                    [lang_country] => en-GB
                    [emoji_flag] => 🇬🇧
                    [currency_code] => GBP
                    [currency_name] => Pound Stirling
                    [currency_name_local] => Pound
                )

            [3] => Array
                ( 
                    [country] => CA
                    [country_name] => Canada
                    [country_name_local] => Canada
                    [lang] => en
                    [name] => Canadian English
                    [lang_country] => en-CA
                    [emoji_flag] => 🇨🇦
                    [currency_code] => CAD
                    [currency_name] => Dollar
                    [currency_name_local] => Canadian Dollar
                )

        )

    [current] => Array
        (
            [country] => US
            [country_name] => United States of America
            [country_name_local] => America
            [lang] => en
            [name] => Amreican English
            [lang_country] => en-US
            [emoji_flag] => 🇺🇸
            [currency_code] => USD
            [currency_name] => Dollar
            [currency_name_local] => US Dollar
        )

)
```
With this array you will be able to create a simple language/country switcher like this in your own frontend framework of choice:

<p align="center">
  <img width="350" src="https://i.ibb.co/yR0DXHy/lang-switcher.png">
</p>

## Usage


### Available methods

``` php
use Jayenne\LaravelLocaleHero\LocaleHero

LocaleHero::countryCode();
/*
 * This will return the two character ISO-3166 code representation of the country.
 * Example: "NL" when locale_code = "nl-NL"
 * Example: "BE" when locale_code = "nl-BE"
 */

LocaleHero::countryName();
/*
 * This will return the primary name of the country.
 * Example: "Netherlads" when locale_code = "nl-NL"
 * Example: "Belgium" when locale_code = "nl-BE"
 */

LocaleHero::countryNameLocal();
/*
 * This will return the name of the country in the language of this file .
 * Example: "Nederland" when locale_code = "nl-NL"
 * Example: "België" when locale_code = "nl-BE"
 */

LocaleHero::languageCode();
/*
 * This will return the right language. This can be a two char representation
 * (example: "nl", dutch) or a four char representation (example: es_CO; Spanish-colombian)
 *
 * You can pass a second argument to override the lang_country. This is helpfull for sending localized emails, when the 
 * session is not the same as the locale for the receiver.
 * Example: Mail::to($request->user())->locale(\LocaleHero::lang('en-US))->send(new OrderShipped($order));
 */
LocaleHero::languageName();
/*
 * This will return the name of the language TRANSLATED IN THE LANGUAGE IN QUESTION.
 * You can use this for nice country-selectors in your app.
 * Example: "English" when locale_code = "en-US"
 * Example: 'België - Vlaams' when locale_code = "nl-BE"
 */

LocaleHero::dateNumbersFormat();
/*
 * Returns string representation of the dateformat with only numbers.
 * Example: "m/d/Y" when locale_code = "en-US"
 * Example: "d/m/Y" when locale_code = "nl-NL"
 */

LocaleHero::dateNumbers($blog->post->created_at);
/*
 * You should provide the date as a Carbon instance;
 * It will return the date as a string in the format for this country.
 * Example: "04/24/2018" when locale_code = "en-US"
 * Example: "24/04/2018" when locale_code = "nl-NL"
 *
 * You can pass a second argument to override the lang_country. This is helpful for sending localized emails when the 
 * session is not the same as the locale for the receiver.
 */

LocaleHero::dateNumbersFullCapitalsFormat();
/*
 * Returns string representation of the dateformat with only capitals, some javascript dateselectors use this.
 * Example: "MM/DD/YYYY" when locale_code = "en-US"
 * Example: "DD-MM-YYYY" when locale_code = "nl-NL"
 */

LocaleHero::dateWordsWithoutDayFormat();
/*
 * Returns string representation of the dateformat with words but without the day.
 * Example: "F jS Y" when locale_code = "en-US"
 * Example: "j F Y" when locale_code = "nl-NL"
 */

LocaleHero::dateWordsWithoutDay($blog->post->created_at);
/*
 * You should provide the date as a Carbon instance;
 * It will return the date in words but without the day.
 * Example: "April 24th 2018" when locale_code = "en-US"
 * Example: "24 april 2018" when locale_code = "nl-NL"
  *
  * You can pass a second argument to override the lang_country. This is helpful for sending localized emails when the 
  * session is not the same as the locale for the receiver.
  */

LocaleHero::dateWordsWithDayFormat();
/*
 * String representation of the dateformat with words including the day.
 * Example: "l F jS Y" when locale_code = "en-US"
 * Example: "l j F Y" when locale_code = "nl-NL"
 */

LocaleHero::dateWordsWithDay($blog->post->created_at);
/*
 * You should provide the date as a Carbon instance;
 * It will return the date in words including the day.
 * Example: "Tuesday April 24th 2018" when locale_code = "en-US"
 * Example: "dinsdag 24 april 2018" when locale_code = "nl-NL"
  *
  * You can pass a second argument to override the lang_country. This is helpful for sending localized emails when the 
  * session is not the same as the locale for the receiver.
  */

LocaleHero::dateBirthdayFormat();
/*
 * String representation of the dateformat for a birthday.
 * Example: "F jS" when locale_code = "en-US"
 * Example: "j F" when locale_code = "nl-NL"
 */

LocaleHero::dateBirthday($user->birthday);
/*
 * You should provide the date as a Carbon instance;
 * It will return the a birthday date.
 * Example: "April 24th" when locale_code = "en-US"
 * Example: "24 april" when locale_code = "nl-NL"
  *
  * You can pass a second argument to override the lang_country. This is helpful for sending localized emails when the 
  * session is not the same as the locale for the receiver.
  */

LocaleHero::timeFormat();
/*
 * Returns string representation of the timeformat.
 * Example: "h:i a" when locale_code = "en-US"
 * Example: "H:i" when locale_code = "nl-NL"
 */

LocaleHero::time($whatever->time);
/*
 * You should provide the time as a Carbon instance;
 * It will return the formatted time.
 * Example: "1:00 pm" when locale_code = "en-US"
 * Example: "13:00" when locale_code = "nl-NL"
  *
  * You can pass a second argument to override the lang_country. This is helpful for sending localized emails when the 
  * session is not the same as the locale for the receiver.
  */

LocaleHero::allLanguages();
/*
 * It will return a collection with all the available languages with all propperties of that language.
 */
 
LocaleHero::langSelectorHelper();
/*
 * It will return an array with the current language, country and name
 * and also the other available language, country and name.
 */

LocaleHero::currencyCode();
/*
 * This will return the ISO-4217 of the country in this file .
 * Example: "EUR" when locale_code = "nl-NL"
 * Example: "COP" when locale_code = "es-CO"
 */

LocaleHero::currencySymbol();
/*
 * This will return the symbol of the officially regognised (primary) currency of the country in this file .
 * Example: currencySymbol() when locale_code = "en-US returns "$"
 * Example: currencySymbol() when locale_code = "es-CO" returns "$"
 *
 * If you pass an optional currency code in to this function then it will return the interantional currency symbol
 * Example: currencySymbol("CAD") when locale_code = "en-CA" returns "$"
 * Example: currencySymbol("USD") when locale_code = "en-CA" returns "US$"
 * Example: currencySymbol("COP") when locale_code = "es-ES" returns "COP"
 * 
 * If the input courrency code doesn't exist it will be ignored and will return the symbol for the current locale
 * Example: currencySymbol("XYZ") when locale_code = "es-ES" returns "€"
 */

LocaleHero::currencyName();
/*
 * This will return the `localized` name of the officially regognised (primary) currency of the country in this file .
 * Example: "Dollar" when locale_code = "en-AU"
 * Example: "Dollar" when locale_code = "en-Au"
 */

LocaleHero::currencyNameLocal();
/*
 * This will return the `localized` name of the officially regognised (primary) currency of the country in this file .
 * Example: "Australian Dollar" when locale_code = "en-AU"
 * Example: "US Dollar" when locale_code = "en-US"
 */
 
 LocaleHero::currencyFormatted(float $amount)
/*
 * return the amount (float value) as a currency value formated to the current locale
 * note: this function requires php extension php_intl.dll
 * en_EN: "£12,345,678.90"
 * en_CA: "$12,345,678.90"
 * fr_CA: "12 345 678,90 $"
 */
```

## What does the middleware do?
The middleware is optional. Of course you can create your own middleware with a different approach. But this is what our “out of the box” middleware does:

* It will check the users browser language preferences. Then it will try to make the most perfect match to the `allowed` lang_country’s in your settings file.
* When no perfect match (language AND country) it will try to make a match on language only.
* When still no match, it will return to your fallback setting.
* It will ALWAYS store a `locale_code` session.
* When a `locale_code` is already set, it will not run any unnecessary scripts.
* Based on the `locale_code` it will check your `resources/lang/` folder for an exact match in your json translation files (example `es_CO`). If an exact match is found it will set the Laravel Locale to this value. This way you are able to create different translation files for each country if you need it.
* When no exact match, it will set the Laravel Locale to the language only.

## Override lang-country properties

Don't like the default settings for a locale? Create a `locale-overrides` directory in your laravel 'resources/lang' directory.
Place a new .json file (example: nl-NL.json) in this directory with your preferred properties. This file will override the package .json file.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ phpunit
```

## How can I help?
Glad your asking! We can always use some more country info in this package. Copy the `_template.json` file in the `src/LocaleHeroData` direcory and fill in the blanks. You can then make a PR.
Some good resources:

* [http://www.localeplanet.com/icu/index.html](http://www.localeplanet.com/icu/index.html)
* [https://gist.github.com/mlconnor/1887156](https://gist.github.com/mlconnor/1887156)
* [http://www.lingoes.net/en/translator/langcode.htm](http://www.lingoes.net/en/translator/langcode.htm)
* [https://emojipedia.org/flags/](https://emojipedia.org/flags/)

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## ToDo

* Caching to reduce file lookups.
* Publish locales as an easier
* Add currency anotation
* Add weights & measures by country

## Security

If you discover any security related issues, please email jayenne@hotmail.com instead of using the issue tracker.

## Credits

- [Stef Rouschop](https://github.com/stefro)
- [Jayenne Montana](https://github.com/jayenne)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.