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
 * This is Wizzy class.
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
    protected $config_repository;

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

        $this->config_repository = $this->app['config'];
        $this->router = $this->app['router'];
        $this->request = $this->app['request'];

        $this->system_requirements = $this->config_repository->get('wizzy.system_requirements');
        $this->steps = $this->config_repository->get('wizzy.steps');
        $this->prefix = $this->config_repository->get('wizzy.prefix', '');
    }

    /**
     * Get wizzy route group prefix from the config file.
     *
     * @return string wizzy.prefix
     */
    public static function getPrefix()
    {
        $config_repository = app()->app['config'];

        return $config_repository->get('wizzy.prefix', '');
    }

    /**
     * Get wizzy default evnironment filename from the config file.
     *
     * @return string wizzy.environment
     */
    public static function getDefaultEnv()
    {
        $config_repository = app()->app['config'];

        return $config_repository->get('wizzy.enviroment', '.env.example');
    }

    /**
     * Get wizzy conclusion view redirect url from the config file.
     *
     * @return string wizzy.redirectTo
     */
    public static function getRedirectUrl()
    {
        $config_repository = app()->app['config'];

        return $config_repository->get('wizzy.redirectTo', '/');
    }

    /**
     * Check if wizzy is enabled from the config file.
     *
     * @return bool true|false
     */
    public static function isWizzyEnabled()
    {
        $config_repository = app()->app['config'];

        return $config_repository->get('wizzy.wizzy_enabled') ? 'true' : 'false';
    }

    /**
     * Check if wizzy environment step is enabled from the config file.
     *
     * @return string wizzy.steps.environment
     */
    public static function isEnvironmentStepEnabled()
    {
        $config_repository = app()->app['config'];

        return $config_repository->get('wizzy.steps.environment') ? 'true' : 'false';
    }

    /**
     * Check if wizzy database step is enabled from the config file.
     *
     * @return string wizzy.steps.database
     */
    public static function isDatabaseStepEnabled()
    {
        $config_repository = app()->app['config'];

        return $config_repository->get('wizzy.steps.database') ? 'true' : 'false';
    }

    /**
     * Stores the $variables array as an environment file. If the $wizzy_enabled
     * variable is true, then it will add WIZZY_ENABLED=false variable in the
     * .env file.
     *
     * @param string $filename
     * @param string $variables
     * @param boolean $wizzy_enabled
     * @return string filename
     */
    public static function environmentStore($filename, $variables, $wizzy_enabled = false)
    {
        if (strlen($filename) == 0) {
            $filename = '.env';
        }

        $file = fopen(base_path($filename), 'w');

        $exploded_variables = explode('|', $variables);

        foreach ($exploded_variables as $variable) {
            $exploded_variable = explode(':', $variable, 2);

            if ($exploded_variable[0] == 'APP_ENV') {
                $config_repository = app()->app['config'];
            }

            fwrite($file, $exploded_variable[0] . '=' . $exploded_variable[1] . "\n");
        }

        if ($wizzy_enabled) {
            fwrite($file, "WIZZY_ENABLED=false\n");
        }

        fclose($file);

        // reset config
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return $filename;
    }

    /**
     * Runs the artisan 'migrate' command.
     *
     * @param string $path
     * @param boolean $refresh_database
     * @param boolean $seed_database
     */
    public static function runMigration($path, $refresh_database, $seed_database)
    {
        // Retrieve force flag
        $config_repository = app()->app['config'];

        $force_flag = $config_repository->get('wizzy.foce_flag');

        // Check database refresh
        if ($refresh_database) {
            Artisan::call('migrate:refresh', ['--seed' => $seed_database]);
        } else {
            // Call migrations
            Artisan::call('migrate', ['--path' => $path, '--force' => $force_flag]);
        }
    }

    /**
     * Retrieve all the migration files in the given path.
     *
     * @param type $path
     * @return array
     */
    public static function getMigrationsList($path)
    {
        $files = [];
        $filesInFolder = File::allFiles(base_path($path));

        foreach ($filesInFolder as $path) {
            array_push($files, pathinfo($path)['basename']);
        }

        return $files;
    }

    /**
     * Runs an artisan command.
     *
     * @param type $command
     * @param type $attributes
     * @return void
     */
    public static function artisanCall($command, $attributes = [])
    {
        Artisan::call($command, $attributes);
    }

    /**
     * Checks the PHP version and returns an array with 3 variables:
     *  - required: true|false
     *  - preferred: true|false
     *  - version: required|preferred|empty string
     * @return array
     */
    public function checkPHPVersion()
    {
        $temp_version = explode('.', phpversion());
        $version = ($temp_version[0] * 10000 + $temp_version[1] * 100 + $temp_version[2]);

        $temp_required_version = explode('.', $this->config_repository->get('wizzy.system_requirements.php.required'));
        $required_version = ($temp_required_version[0] * 10000 + $temp_required_version[1] * 100 + $temp_required_version[2]);

        $temp_preferred_version = explode('.', $this->config_repository->get('wizzy.system_requirements.php.preferred'));
        $preferred_version = ($temp_preferred_version[0] * 10000 + $temp_preferred_version[1] * 100 + $temp_preferred_version[2]);

        return [
            'required' => ($version < $required_version),
            'preferred' => ($version < $preferred_version),
            'version' => ($version < $required_version ? $this->config_repository->get('wizzy.system_requirements.php.required') : $version < $preferred_version ? $this->config_repository->get('wizzy.system_requirements.php.preferred') : ''),
        ];
    }

    /**
     * Checks the PHP extensions and returns an array with the variable name
     * and version|false.
     *
     * @return array
     */
    public function checkPHPExstensions()
    {
        $raw_extensions = $this->config_repository->get('wizzy.system_requirements.php_extensions');
        $extensions = [];

        foreach ($raw_extensions as $extension) {
            $extensions[$extension] = phpversion($extension);
        }

        return $extensions;
    }

    /**
     * Returns all the environment variables as an array.
     *
     * @param string $envPath
     * @return array
     */
    public function fromEnvToArray($envPath)
    {
        $env_lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env_variables = [];

        foreach ($env_lines as $line) {
            // Check if comment
            if (substr($line, 0, 1) !== '#') {
                // Not a comment, explode line
                $variable = explode('=', $line);
                if ($variable[0] != 'WIZZY_ENABLED') {
                    $env_variables[$variable[0]] = $variable[1];
                }
            }
        }

        return $env_variables;
    }

    /**
     * Returns all the environment variables as a string with this structure:
     *
     * key:value|key:value|key:value
     *
     * @param string $envPath
     * @return string
     */
    public function fromEnvToString($envPath)
    {
        $env_lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $env_variables = '';

        for ($i = 0; $i < count($env_lines); $i++) {
            $line = $env_lines[$i];
            // Check if comment
            if (substr($line, 0, 1) !== '#') {
                // Not a comment, explode line
                $variable = explode('=', $line);
                if ($variable[0] != 'WIZZY_ENABLED') {
                    $env_variables .= $variable[0] . ':' . $variable[1];
                }
            }

            if ($i < count($env_lines) - 1) {
                $env_variables .= '|';
            }
        }

        return $env_variables;
    }

}
