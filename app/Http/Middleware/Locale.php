<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('locale') ?? config('app.locale');

        App::setLocale($locale);

        Log::info('local value from query string is {value}', ['value' => App::getLocale()]);

        Log::info(config('database.default'));

        Log::info('hello world');

        Log::info(config('session.driver'));

        Log::info('hello world');

        return $next($request);
    }
}
