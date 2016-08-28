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

use Closure;
use IlGala\LaravelWizzy\Wizzy;

/**
 * This is the Wizzy middleware class.
 *
 * @author ilgala
 */
class WizzyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $wizzy_enabled = env('WIZZY_ENABLED', null);

        if ($wizzy_enabled == null) {
            $wizzy_enabled = Wizzy::isWizzyEnabled() == 'true';
        }

        if ($wizzy_enabled && !$request->is(Wizzy::getPrefix().'/*')) {
            // Redirect to wizard
            return redirect()->route(Wizzy::getPrefix().'.wizzy');
        } elseif (session()->has('wizzy.envfile')) {
            session()->forget('wizzy.envfile');
        }

        return $next($request);
    }
}
