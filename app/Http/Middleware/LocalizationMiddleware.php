<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if there is a 'locale' saved in the session
        if (Session::has('locale')) {
            // Set the application locale to that value
            App::setLocale(Session::get('locale'));
        }

        return $next($request);
    }
}