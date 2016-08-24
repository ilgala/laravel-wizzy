<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\LaravelWizzy;

use Artisan;
use File;

/**
 * Description of Wizzy
 *
 * @author ilgala
 */
class Wizzy
{

    /**
     * Config repository.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $configRepository;

    /**
     * Illuminate router class.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * Illuminate request class.
     *
     * @var \Illuminate\Routing\Request
     */
    protected $request;

    /**
     * Illuminate request class.
     *
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Application system requirements array.
     *
     * @var array
     */
    private $system_requirements;

    /**
     * Wizzy enabled steps.
     *
     * @var array
     */
    private $steps;

    /**
     * Wizzy route group prefix.
     *
     * @var string
     */
    private $prefix;

    /**
     * Creates new instance.
     */
    public function __construct()
    {
        $this->app = app();

        $this->configRepository = $this->app['config'];
        $this->router = $this->app['router'];
        $this->request = $this->app['request'];

        $this->system_requirements = $this->configRepository->get('wizzy.system_requirements');
        $this->steps = $this->configRepository->get('wizzy.steps');
        $this->prefix = $this->configRepository->get('wizzy.prefix', '');
    }

    /**
     * Get wizzy route group prefix
     */
    public static function getPrefix()
    {
        $configRepository = app()->app['config'];
        return $configRepository->get('wizzy.prefix', '');
    }

    /**
     * Get wizzy route group prefix
     */
    public static function getDefaultEnv()
    {
        $configRepository = app()->app['config'];
        return $configRepository->get('wizzy.enviroment', '.env.example');
    }

    public static function isEnvironmentStepEnabled()
    {
        $configRepository = app()->app['config'];

        return $configRepository->get('wizzy.steps.environment') ? 'true' : 'false';
    }

    public static function isDatabaseStepEnabled()
    {
        $configRepository = app()->app['config'];

        return $configRepository->get('wizzy.steps.database') ? 'true' : 'false';
    }

    public function checkPHPVersion()
    {
        $temp_version = explode('.', phpversion());
        $version = ($temp_version[0] * 10000 + $temp_version[1] * 100 + $temp_version[2]);

        $temp_required_version = explode('.', $this->configRepository->get('wizzy.system_requirements.php.required'));
        $required_version = ($temp_required_version[0] * 10000 + $temp_required_version[1] * 100 + $temp_required_version[2]);

        $temp_preferred_version = explode('.', $this->configRepository->get('wizzy.system_requirements.php.preferred'));
        $preferred_version = ($temp_preferred_version[0] * 10000 + $temp_preferred_version[1] * 100 + $temp_preferred_version[2]);

        return [
            'required' => ($version < $required_version),
            'preferred' => ($version < $preferred_version),
            'version' => ($version < $required_version ? $this->configRepository->get('wizzy.system_requirements.php.required') : $version < $preferred_version ? $this->configRepository->get('wizzy.system_requirements.php.preferred') : '')
        ];
    }

    public function checkPHPExstensions()
    {
        $raw_extensions = $this->configRepository->get('wizzy.system_requirements.php_extensions');
        $extensions = [];

        foreach ($raw_extensions as $extension) {
            $extensions[$extension] = phpversion($extension);
        }

        return $extensions;
    }

    public function fromEnvToArray($envPath)
    {
        $env_lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env_variables = [];

        foreach ($env_lines as $line) {
            // Check if comment
            if (substr($line, 0, 1) !== '#') {
                // Not a comment, explode line
                $variable = explode('=', $line);
                $env_variables[$variable[0]] = $variable[1];
            }
        }

        return $env_variables;
    }

    public function checkEnvFilename($filename)
    {
        if (strlen($filename) > 0 && substr($filename, 0, 1) === '.') {
            $filename = substr($filename, 1, strlen($filename));
        }

        return $filename;
    }

    public static function environmentStore($filename, $variables)
    {
        if (strlen($filename) == 0) {
            $filename = '.env';
        }

        $file = fopen(base_path($filename), 'w');

        $exploded_variables = explode('|', $variables);

        foreach ($exploded_variables as $variable) {
            $exploded_variable = explode(':', $variable);
            fwrite($file, $exploded_variable[0] . "=" . $exploded_variable[1] . "\n");
        }

        fclose($file);

        return $filename;
    }

    public static function runMigration($path, $refresh_database, $seed_database)
    {
        // Retrieve force flag
        $configRepository = app()->app['config'];

        $force_flag = $configRepository->get('wizzy.foce_flag');

        // Check database refresh
        if ($refresh_database) {
            Artisan::call('migrate:refresh', array('--seed' => $seed_database));
        }

        // Call migrations
        Artisan::call('migrate', array('--path' => $path, '--force' => $force_flag));
    }

    public static function getMigrationsList($path)
    {
        $files = [];
        $filesInFolder = File::allFiles(base_path($path));

        foreach ($filesInFolder as $path) {
            array_push($files, pathinfo($path)['basename']);
        }

        return $files;
    }

}
