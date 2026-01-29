<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Available locales.
     */
    protected array $availableLocales = ['zh-TW', 'zh-CN', 'en'];

    /**
     * Switch the application locale.
     */
    public function switch(Request $request, string $locale)
    {
        if (!in_array($locale, $this->availableLocales)) {
            abort(400, 'Invalid locale');
        }

        // Store in session
        Session::put('locale', $locale);

        // Update user preference if authenticated
        if ($request->user()) {
            $request->user()->update(['preferred_language' => $locale]);
        }

        return redirect()->back();
    }
}
