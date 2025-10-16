<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function ajaxSwitch(Request $request)
{
    $locale = $request->input('locale');

    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        return response()->json(['success' => true, 'locale' => $locale]);
    }

    return response()->json(['success' => false], 400);
}

    public function switch($locale)
    {
        // dd($locale);
        if (in_array($locale, ['ar', 'en'])) {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }

        return redirect()->back();
    }
}

