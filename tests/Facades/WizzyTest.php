<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\Tests\LaravelWizzy\Facades;

use GrahamCampbell\TestBenchCore\FacadeTrait;
use IlGala\Tests\LaravelWizzy\AbstractTestCase;
use IlGala\LaravelWizzy\Facades\Wizzy as Facade;
use IlGala\LaravelWizzy\Wizzy;

/**
 * This is the Wizzy facade test class.
 *
 * @author ilgala
 */
class WizzyTest extends AbstractTestCase
{

    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'wizzy';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return Facade::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return Wizzy::class;
    }

}
