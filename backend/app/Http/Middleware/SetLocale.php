<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->determineLocale($request);

        if ($this->isValidLocale($locale)) {
            App::setLocale($locale);
        }

        return $next($request);
    }

    /**
     * Determine the locale to use
     */
    protected function determineLocale(Request $request): string
    {
        // 1. Check explicit locale parameter in request
        if ($request->has('locale') && $this->isValidLocale($request->get('locale'))) {
            return $request->get('locale');
        }

        // 2. Check Accept-Language header
        if ($request->hasHeader('Accept-Language')) {
            $acceptLanguage = $request->header('Accept-Language');
            $preferredLocale = $this->parseAcceptLanguage($acceptLanguage);
            if ($this->isValidLocale($preferredLocale)) {
                return $preferredLocale;
            }
        }

        // 3. Check user's saved preference (if authenticated)
        if (Auth::check() && Auth::user()->locale) {
            $userLocale = Auth::user()->locale;
            if ($this->isValidLocale($userLocale)) {
                return $userLocale;
            }
        }

        // 4. Check session (only if session is available)
        if ($request->hasSession() && $request->session()->has('locale')) {
            $sessionLocale = $request->session()->get('locale');
            if ($this->isValidLocale($sessionLocale)) {
                return $sessionLocale;
            }
        }

        // 5. Check cookie
        if ($request->hasCookie('locale')) {
            $cookieLocale = $request->cookie('locale');
            if ($this->isValidLocale($cookieLocale)) {
                return $cookieLocale;
            }
        }

        // 6. Device/geo-location hint (if provided)
        if ($request->hasHeader('X-Device-Locale')) {
            $deviceLocale = $request->header('X-Device-Locale');
            if ($this->isValidLocale($deviceLocale)) {
                return $deviceLocale;
            }
        }

        // 7. Fallback to app default
        return config('app.locale', 'en');
    }

    /**
     * Check if locale is valid
     */
    protected function isValidLocale(string $locale): bool
    {
        $supportedLocales = ['en', 'ar', 'tr'];
        return in_array($locale, $supportedLocales);
    }

    /**
     * Parse Accept-Language header and return the most preferred supported locale
     */
    protected function parseAcceptLanguage(string $acceptLanguage): string
    {
        $languages = [];

        // Parse Accept-Language header
        $parts = explode(',', $acceptLanguage);

        foreach ($parts as $part) {
            $part = trim($part);
            if (strpos($part, ';q=') !== false) {
                [$lang, $quality] = explode(';q=', $part);
                $languages[trim($lang)] = (float) $quality;
            } else {
                $languages[trim($part)] = 1.0;
            }
        }

        // Sort by quality (highest first)
        arsort($languages);

        // Find the first supported language
        foreach ($languages as $lang => $quality) {
            // Handle language-region format (e.g., en-US, ar-SA)
            if (strpos($lang, '-') !== false) {
                $lang = substr($lang, 0, strpos($lang, '-'));
            }

            if ($this->isValidLocale($lang)) {
                return $lang;
            }
        }

        return config('app.locale', 'en');
    }
}
