<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class EnableCrossRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->getMethod() == "OPTIONS") {
            $response = new Response('OK', 200);
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, X-Auth-Token, Accept');
            $response->header('Access-Control-Allow-Methods', 'GET,POST,PATCH,PUT,DELETE,OPTIONS');
            $response->header('Content-Type', 'application/json;charset=utf-8');

            return $response;
        }

        $response = $next($request);
        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Content-Type', 'application/json;charset=utf-8');

        return $response;
    }
}
