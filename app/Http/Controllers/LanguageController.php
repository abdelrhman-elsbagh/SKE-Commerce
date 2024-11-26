<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function changeLanguage($lang)
    {
        if (in_array($lang, ['ar', 'en'])) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        }
        return redirect()->back();
    }

}
