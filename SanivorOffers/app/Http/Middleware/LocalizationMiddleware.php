<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Priority: 1) session override (from switcher), 2) user's saved preference, 3) default 'de'
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } elseif (Auth::check() && Auth::user()->locale) {
            $locale = Auth::user()->locale;
            Session::put('locale', $locale);
            App::setLocale($locale);
        } else {
            Session::put('locale', 'de');
            App::setLocale('de');
        }

        return $next($request);
    }
}
