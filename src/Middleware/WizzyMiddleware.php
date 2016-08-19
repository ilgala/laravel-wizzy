<?php

namespace IlGala\LaravelWizzy\Middleware;

use IlGala\LaravelWizzy\Wizzy;
use Closure;

class WizzyMiddleware
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
        $wizzy = env('WIZZY_ENABLED', false);

        if ($wizzy && !$request->is(Wizzy::getPrefix() . '/*')) {
            return redirect()->route(Wizzy::getPrefix() . '.welcome');
        }

        return $next($request);
    }

}
