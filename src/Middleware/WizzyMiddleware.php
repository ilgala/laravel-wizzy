<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
            return redirect()->route(Wizzy::getPrefix() . '.wizzy');
        }

        return $next($request);
    }

}
