<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    public function setLang($locale)
    {
        $allowed = ['de', 'en'];
        if (!in_array($locale, $allowed)) {
            $locale = 'de';
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        // Persist to the user's profile so the choice survives logout/login.
        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        return redirect()->back();
    }
}
