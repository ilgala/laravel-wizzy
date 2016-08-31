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
use Illuminate\Contracts\Config\Repository;

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
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Creates new instance.
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Get the config instance.
     *
     * @return \Illuminate\Contracts\Config\Repository
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'wizzy';
    }

    /**
     * Get wizzy route group prefix from the config file.
     *
     * @return string wizzy.prefix
     */
    public function getPrefix()
    {
        return $this->config->get($this->getConfigName() . '.prefix', '');
    }

    /**
     * Get wizzy default evnironment filename from the config file.
     *
     * @return string wizzy.environment
     */
    public function getDefaultEnv()
    {
        return $this->config->get($this->getConfigName() . '.environment', '.env');
    }

    /**
     * Get wizzy conclusion view redirect url from the config file.
     *
     * @return string wizzy.redirectTo
     */
    public function getRedirectUrl()
    {
        return $this->config->get($this->getConfigName() . '.redirectTo', '/');
    }

    /**
     * Retrieve all the migration files in the given path.
     *
     * @param type $path
     *
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
     * Check if wizzy is enabled from the config file.
     *
     * @return bool true|false
     */
    public function isWizzyEnabled()
    {
        return $this->config->get($this->getConfigName() . '.wizzy_enabled', false) ? 'true' : 'false';
    }

    /**
     * Check if wizzy environment step is enabled from the config file.
     *
     * @return string wizzy.steps.environment
     */
    public function isEnvironmentStepEnabled()
    {
        return $this->config->get($this->getConfigName() . '.steps.environment', false) ? 'true' : 'false';
    }

    /**
     * Check if wizzy database step is enabled from the config file.
     *
     * @return string wizzy.steps.database
     */
    public function isDatabaseStepEnabled()
    {
        return $this->config->get($this->getConfigName() . '.steps.database', false) ? 'true' : 'false';
    }

    /**
     * Stores the $variables array as an environment file. If the $wizzy_enabled
     * variable is true, then it will add WIZZY_ENABLED=false variable in the
     * .env file.
     *
     * @param string $filename
     * @param string $variables
     * @param bool   $wizzy_enabled
     *
     * @return string filename
     */
    public function environmentStore($filename, $variables, $wizzy_enabled = false)
    {
        if (strlen($filename) == 0) {
            $filename = '.env';
        }

        $file = fopen(base_path($filename), 'w');

        $exploded_variables = explode('|', $variables);

        foreach ($exploded_variables as $variable) {
            $exploded_variable = explode(':', $variable, 2);
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
     * @param bool   $refresh_database
     * @param bool   $seed_database
     */
    public function runMigration($path, $refresh_database, $seed_database)
    {
        // Retrieve force flag
        $force_flag = $this->config->get($this->getConfigName() . '.foce_flag');

        // Check database refresh
        if ($refresh_database) {
            Artisan::call('migrate:refresh', ['--seed' => $seed_database]);
        } else {
            // Call migrations
            Artisan::call('migrate', ['--path' => $path, '--force' => $force_flag]);
        }
    }

    /**
     * Runs an artisan command.
     *
     * @param type $command
     * @param type $attributes
     *
     * @return void
     */
    public function artisanCall($command, $attributes = [])
    {
        Artisan::call($command, $attributes);
    }

    /**
     * Checks the PHP version and returns an array with 3 variables:
     *  - required: true|false
     *  - preferred: true|false
     *  - version: required|preferred|empty string.
     *
     * @return array
     */
    public function checkPHPVersion()
    {
        $temp_version = explode('.', phpversion());
        $version = ($temp_version[0] * 10000 + $temp_version[1] * 100 + $temp_version[2]);

        $temp_required_version = explode('.', $this->config->get($this->getConfigName() . '.system_requirements.php.required'));
        $required_version = ($temp_required_version[0] * 10000 + $temp_required_version[1] * 100 + $temp_required_version[2]);

        $temp_preferred_version = explode('.', $this->config->get($this->getConfigName() . '.system_requirements.php.preferred'));
        $preferred_version = ($temp_preferred_version[0] * 10000 + $temp_preferred_version[1] * 100 + $temp_preferred_version[2]);

        return [
            'required' => ($version < $required_version),
            'preferred' => ($version < $preferred_version),
            'version' => ($version < $required_version ? $this->config->get($this->getConfigName() . '.system_requirements.php.required') : $version < $preferred_version ? $this->config->get($this->getConfigName() . '.system_requirements.php.preferred') : ''),
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
        $raw_extensions = $this->config->get($this->getConfigName() . '.system_requirements.php_extensions');
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
     *
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
     * Returns all the environment variables as a string with this structure:.
     *
     * key:value|key:value|key:value
     *
     * @param string $envPath
     *
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
