<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class LocaleController extends Controller
{
    /**
     * Get available locales
     */
    public function getAvailableLocales(): JsonResponse
    {
        $locales = [
            'en' => [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'rtl' => false,
            ],
            'ar' => [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'rtl' => true,
            ],
            'tr' => [
                'code' => 'tr',
                'name' => 'Turkish',
                'native_name' => 'Türkçe',
                'rtl' => false,
            ],
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'locales' => array_values($locales),
                'current_locale' => App::getLocale(),
                'fallback_locale' => config('app.fallback_locale', 'en'),
            ]
        ]);
    }

    /**
     * Get translations for current locale
     */
    public function getTranslations(Request $request): JsonResponse
    {
        $locale = $request->input('locale', App::getLocale());
        
        if (!$this->isValidLocale($locale)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid locale'
            ], 422);
        }

        $translationPath = resource_path("lang/{$locale}.json");
        
        if (!file_exists($translationPath)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Translation file not found'
            ], 404);
        }

        $translations = json_decode(file_get_contents($translationPath), true);

        return response()->json([
            'status' => 'success',
            'data' => [
                'locale' => $locale,
                'translations' => $translations,
            ]
        ]);
    }

    /**
     * Set user's preferred locale
     */
    public function setUserLocale(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'locale' => 'required|string|in:en,ar,tr',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $locale = $request->input('locale');

        // If user is authenticated, save to their profile
        if (Auth::check()) {
            $user = Auth::user();
            $user->update(['locale' => $locale]);
        }

        // Set for current session
        App::setLocale($locale);
        session(['locale' => $locale]);

        return response()->json([
            'status' => 'success',
            'message' => 'Locale updated successfully',
            'data' => [
                'locale' => $locale,
                'user_saved' => Auth::check(),
            ]
        ])->withCookie(cookie('locale', $locale, 60*24*365)); // 1 year cookie
    }

    /**
     * Get current locale information
     */
    public function getCurrentLocale(): JsonResponse
    {
        $currentLocale = App::getLocale();
        
        $localeInfo = match($currentLocale) {
            'ar' => [
                'code' => 'ar',
                'name' => 'Arabic',
                'native_name' => 'العربية',
                'rtl' => true,
            ],
            'tr' => [
                'code' => 'tr',
                'name' => 'Turkish',
                'native_name' => 'Türkçe',
                'rtl' => false,
            ],
            default => [
                'code' => 'en',
                'name' => 'English',
                'native_name' => 'English',
                'rtl' => false,
            ],
        };

        return response()->json([
            'status' => 'success',
            'data' => [
                'current_locale' => $localeInfo,
                'user_locale' => Auth::check() ? Auth::user()->locale : null,
                'session_locale' => session('locale'),
                'cookie_locale' => request()->cookie('locale'),
            ]
        ]);
    }

    /**
     * Check if locale is valid
     */
    private function isValidLocale(string $locale): bool
    {
        return in_array($locale, ['en', 'ar', 'tr']);
    }
}
