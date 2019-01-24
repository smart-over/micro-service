<?php

namespace SmartOver\MicroService\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class Locale
 *
 * @package SmartOver\MicroService\Middleware
 */
class Locale
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->header('Locale')) {

            app('translator')->setLocale($request->header('Locale'));
        }

        return $next($request);
    }
}