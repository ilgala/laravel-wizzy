<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
      |--------------------------------------------------------------------------
      | Wizzy Enable flag
      |--------------------------------------------------------------------------
      |
      | This option specifies if the wizzy is enabled and will be considered only
      | if the .env file doesn't contain any WIZZY_ENABLED key.
      |
      | Default: true
      |
     */
    'wizzy_enabled' => true,
    /*
      |--------------------------------------------------------------------------
      | Application System Requirements
      |--------------------------------------------------------------------------
      |
      | This set of options specifies the application requirements for php and
      | the filesystem permissions. Only the required PHP version is mandatory for
      | the wizard.
      |
      |
     */
    'system_requirements' => [
        'php' => [
            'required'  => '5.5.9',
            'preferred' => '5.5.9',
        ],
        'php_extensions' => [
            'OpenSSL',
            'PDO',
            'Mbstring',
            'Tokenizer',
        ],
        'permissions' => [
            'storage/app/'       => '775',
            'storage/framework/' => '775',
            'storage/logs/'      => '775',
            'bootstrap/cache/'   => '775',
        ],
    ],
    /*
      |--------------------------------------------------------------------------
      | Wizzy Steps
      |--------------------------------------------------------------------------
      |
      | This set of options specifies if the environment view and the database
      | view are enabled in the wizard. If the parameters are setted to false,
      | the wizard will skip one or both steps.
      |
     */
    'steps' => [
        'environment' => true,
        'database'    => true,
    ],
    /*
      |--------------------------------------------------------------------------
      | Wizzy Routes Prefix
      |--------------------------------------------------------------------------
      |
      | This option specifies Wizzy's routes group prefix in order to avoid
      | conflicts with other routes of the application.
      |
      | Default: install
     */
    'prefix' => 'install',
    /*
      |--------------------------------------------------------------------------
      | Environment Filename
      |--------------------------------------------------------------------------
      |
      | Application's environment file used to parse the environment variables.
      |
      | Default: .env
     */
    'environment' => '.env',
    /*
      |--------------------------------------------------------------------------
      | Migrations Path
      |--------------------------------------------------------------------------
      |
      | Application's path to migrations files.
      |
      | Default: database/migrations
     */
    'migrations_path' => 'database/migrations',
    /*
      |--------------------------------------------------------------------------
      | Force Flag
      |--------------------------------------------------------------------------
      |
      | If this option is setted to true, the migration command will be runned
      | with --force attribute.
      |
      | Default: false
     */
    'force_flag' => false,
    /*
      |--------------------------------------------------------------------------
      | Conclusion Scripts
      |--------------------------------------------------------------------------
      |
      | This set of options contains all the artisan scripts that will be runned
      | during the last step of the wizard.
      |
     */
    'conclusion_scripts' => [
        'clear-compiled',
        'optimize',
        'config:clear',
        'config:cache',
    ],
    /*
      |--------------------------------------------------------------------------
      | Redirect To
      |--------------------------------------------------------------------------
      |
      | This is the url used to redirect the user when the application install
      | process is completed.
      |
      | Default: /
     */
    'redirectTo' => '/',
];
