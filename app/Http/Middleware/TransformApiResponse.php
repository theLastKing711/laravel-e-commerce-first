<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TransformApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        return $next($request);

        //        $response = $next($request);

        //        if($response->isSuccessful())
        //        {
        //            if($request->method() == 'POST')
        //            {
        //                $response->setStatusCode(201)
        //            }
        //        }

    }
}
