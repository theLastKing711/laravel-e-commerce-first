<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// convert route?is_condition=true&is_other_=false
// which get translated to string 'true' or 'false
// to true or false
// so laravel validation don't throw error
//when there is none
class ParseStringToBoolInQueryParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        foreach ($request->query() as $key => $value) {
            if ($value === 'true') {
                $request[$key] = true;
            }
            if ($value === 'false') {
                $request[$key] = false;
            }
        }

        return $next($request);
    }
}
