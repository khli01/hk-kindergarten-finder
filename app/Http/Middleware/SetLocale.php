<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Available locales.
     */
    protected array $availableLocales = ['zh-TW', 'zh-CN', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: URL parameter > Session > User preference > Browser > Default
        $locale = $this->determineLocale($request);
        
        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }

    /**
     * Determine the locale to use.
     */
    protected function determineLocale(Request $request): string
    {
        // 1. Check URL parameter
        if ($request->has('lang') && in_array($request->get('lang'), $this->availableLocales)) {
            return $request->get('lang');
        }

        // 2. Check session
        if (Session::has('locale') && in_array(Session::get('locale'), $this->availableLocales)) {
            return Session::get('locale');
        }

        // 3. Check authenticated user's preference
        if ($request->user() && in_array($request->user()->preferred_language, $this->availableLocales)) {
            return $request->user()->preferred_language;
        }

        // 4. Check browser Accept-Language header
        $browserLocale = $this->getBrowserLocale($request);
        if ($browserLocale) {
            return $browserLocale;
        }

        // 5. Fall back to default
        return config('app.locale', 'zh-TW');
    }

    /**
     * Get locale from browser Accept-Language header.
     */
    protected function getBrowserLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return null;
        }

        // Parse Accept-Language header
        $languages = explode(',', $acceptLanguage);
        
        foreach ($languages as $language) {
            $lang = trim(explode(';', $language)[0]);
            
            // Map common language codes to our locales
            if (str_starts_with($lang, 'zh-TW') || str_starts_with($lang, 'zh-Hant')) {
                return 'zh-TW';
            }
            if (str_starts_with($lang, 'zh-CN') || str_starts_with($lang, 'zh-Hans') || $lang === 'zh') {
                return 'zh-CN';
            }
            if (str_starts_with($lang, 'en')) {
                return 'en';
            }
        }

        return null;
    }
}
