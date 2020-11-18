<?php
namespace Jayenne\LaravelLocaleHero\Controllers;

use Illuminate\Routing\Controller;

class LocaleHeroSwitchController extends Controller
{
    public function switch($locale_code)
    {
        // If a locale_code does not match any of the allowed,
        // go back without doing anything.
        if (! in_array($locale_code, config('locale-hero.allowed'))) {
            return redirect()->back();
        }

        // Set the right sessions
        \LocaleHero::setAllSessions($locale_code);

        // If a user is logged in and it has a locale propperty, set the new locale.
        if (\Auth::user()
            && array_key_exists('locale_code', \Auth::user()->getAttributes())
        ) {
            try {
                \Auth::user()->locale_code = $locale_code;
                \Auth::user()->save();
            } catch (\Exception $e) {
                \Log::error(get_class($this).' at '.__LINE__.': '.$e->getMessage());
            }
        }

        return redirect()->back();
    }
}
