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

use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use IlGala\LaravelWizzy\Wizzy;
use Illuminate\Contracts\Config\Repository;
use Mockery;

/**
 * This is the wizzy test class.
 *
 * @author ilgala
 */
class WizzyTest extends AbstractTestBenchTestCase
{

    protected $defaults = [
        'wizzy_enabled' => true,
        'system_requirements' => [
            'php' => [
                'required' => '5.5.9',
                'preferred' => '5.5.9',
            ],
            'php_extensions' => [
                'OpenSSL',
                'PDO',
                'Mbstring',
                'Tokenizer',
            ],
            'permissions' => [
                'storage/app/' => '775',
                'storage/framework/' => '775',
                'storage/logs/' => '775',
                'bootstrap/cache/' => '775',
            ],
        ],
        'steps' => [
            'environment' => true,
            'database' => true,
        ],
        'prefix' => 'install',
        'environment' => '.env',
        'migrations_path' => 'database/migrations',
        'force_flag' => false,
        'conclusion_scripts' => [
            'clear-compiled',
            'optimize',
            'config:clear',
            'config:cache',
        ],
        'redirectTo' => '/',
    ];

    /**
     *
     */
    public function testGetPrefix()
    {
        $wizzy = $this->getWizzy();

        $wizzy->getConfig()->shouldReceive('get')->once()
                ->with('wizzy.prefix', '')->andReturn($this->defaults['prefix']);
        
        $this->assertSame('install', $wizzy->getPrefix());
    }

    public function testGetDefaultEnv()
    {
        $wizzy = $this->getWizzy();

        $wizzy->getConfig()->shouldReceive('get')->once()
                ->with('wizzy.environment', '.env')->andReturn($this->defaults['environment']);
        
        $this->assertSame('.env', $wizzy->getDefaultEnv());
    }

    public function testGetRedirectUrl()
    {
        $wizzy = $this->getWizzy();

        $wizzy->getConfig()->shouldReceive('get')->once()
                ->with('wizzy.redirectTo', '/')->andReturn($this->defaults['redirectTo']);
        
        $this->assertSame('/', $wizzy->getRedirectUrl());
    }

    public function testIsWizzyEnabled()
    {
        $wizzy = $this->getWizzy();

        $wizzy->getConfig()->shouldReceive('get')->once()
                ->with('wizzy.wizzy_enabled', false)->andReturn($this->defaults['wizzy_enabled']);
        
        $this->assertSame('true', $wizzy->isWizzyEnabled());
    }

    public function testIsEnvironmentStepEnabled()
    {
        $wizzy = $this->getWizzy();

        $wizzy->getConfig()->shouldReceive('get')->once()
                ->with('wizzy.steps.environment', false)->andReturn($this->defaults['steps']['environment']);
        
        $this->assertSame('true', $wizzy->isEnvironmentStepEnabled());
    }

    public function testIsDatabaseStepEnabled()
    {
        $wizzy = $this->getWizzy();

        $wizzy->getConfig()->shouldReceive('get')->once()
                ->with('wizzy.steps.database', false)->andReturn($this->defaults['steps']['database']);
        
        $this->assertSame('true', $wizzy->isDatabaseStepEnabled());
    }
//
//    public function testEnvironmentStore()
//    {
//        // TO DO
//    }
//
//    public function testRunMigration()
//    {
//        // TO DO
//    }
//
//    public function testGetMigrationsList()
//    {
//        // TO DO
//    }
//
//    public function testArtisanCall()
//    {
//        // TO DO
//    }
//
//    public function testCheckPHPVersion()
//    {
//        // TO DO
//    }
//
//    public function testCheckPHPExtensions()
//    {
//        // TO DO
//    }

    public function testFromEnvToArray()
    {
        $wizzy = $this->getWizzy();
        
        $this->assertSame(['TEST_ENV' => '1','OTHER_TEST' => 'string'], $wizzy->fromEnvToArray(__DIR__ . '/files/.env.test'));
    }

    public function testFromEnvToString()
    {
        $wizzy = $this->getWizzy();
        
        $this->assertSame('TEST_ENV:1|OTHER_TEST:string', $wizzy->fromEnvToString(__DIR__ . '/files/.env.test'));
    }

    protected function getWizzy()
    {
        $repository = Mockery::mock(Repository::class);
        
//        error_log($repository->get('wizzy', 'NONE'));
        
        $wizzy = new Wizzy($repository);

        return $wizzy;
    }

}
