<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\Tests\LaravelWizzy;

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use IlGala\LaravelWizzy\Wizzy;

/**
 * This is the service provider test class.
 *
 * @author ilgala
 */
class WizzyServiceProviderTest extends AbstractTestCase
{

    use ServiceProviderTrait;

    public function testWizzyIsInjectable()
    {
        $this->assertIsInjectable(Wizzy::class);
    }

}
