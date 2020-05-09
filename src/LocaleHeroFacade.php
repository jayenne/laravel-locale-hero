<?php

namespace Jayenne\LaravelLocaleHero;

use Illuminate\Support\Facades\Facade;

class LocaleHeroFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LocaleHero::class;
    }
}
