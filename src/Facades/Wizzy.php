<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\LaravelWizzy\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Description of WizzyFacade
 *
 * @author ilgala
 */
class Wizzy extends Facade
{

    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Wizzy'; // the IoC binding.
    }

}
