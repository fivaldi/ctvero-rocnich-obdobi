<?php

namespace App\Http\Middleware;

use Closure;

class ClientLocale
{
    // https://stackoverflow.com/a/48133154 - thank you!
    public function handle($request, Closure $next)
    {
        if ($request->session()->has('locale')) {
            app('translator')->setLocale($request->session()->get('locale'));
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
            $request->session()->put('locale', $clientLocale ?? config('app.fallback_locale'));
        }

        return $next($request);
    }
}
