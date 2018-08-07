<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $header = 'X-Name')
    {
        /**
         * Construir la respuesta y luego actuar sobre esa respuesta
         */
        $response = $next($request);

        $response->headers->set($header, config('app.name'));

        return $response;
    }
}
