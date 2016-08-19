<?php

namespace IlGala\LaravelWizzy\Facades;

/**
 * Description of WizzyFacade
 *
 * @author ilgala
 */
class WizzyFacade extends Facade
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
