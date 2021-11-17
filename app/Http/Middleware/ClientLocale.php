<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class ClientLocale
{
    // https://stackoverflow.com/a/48133154 - thank you!
    public function handle($request, Closure $next)
    {
        if (Session::has('locale')) {
            app('translator')->setLocale(Session::get('locale'));
        } else {
            $clientLocales = preg_split('/,|;/', $request->server('HTTP_ACCEPT_LANGUAGE'));
            foreach ($clientLocales as $locale) {
                $localeWithoutVariant = preg_split('/-/', $locale)[0];
                if (in_array($localeWithoutVariant, config('ctvero.locales'))) {
                    $clientLocale = $localeWithoutVariant;
                    break;
                }
            }
            app('translator')->setLocale($clientLocale ?? config('app.fallback_locale'));
            Session::put('locale', $clientLocale ?? config('app.fallback_locale'));
        }

        return $next($request);
    }
}
