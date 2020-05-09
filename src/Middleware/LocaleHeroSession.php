<?php

namespace Jayenne\LaravelLocaleHero\Middleware;

use App;
use Closure;

class LocaleHeroSession
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! session()->has('locale_code')) {
            \LocaleHero::setAllSessions($request->server('HTTP_ACCEPT_LANGUAGE'));
        }

        // Set the right locale for the laravel App and also for Date (https://github.com/jenssegers/date)
        App::setLocale(session('locale'));

        return $next($request);
    }
}
