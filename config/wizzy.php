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
     * Application system requirements
     */
    'system_requirements' => [
        'php' => [
            'required' => '5.5.9',
            'preferred' => '5.5.9'
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
            'bootstrap/cache/' => '775'
        ]
    ],
    /*
     * Enable/disable wizard steps
     */
    'steps' => [
        'environment' => true,
        'database' => true,
    ],
    /*
     * Wizzy route group prefix
     */
    'prefix' => 'install',
    /*
     * Environment file
     */
    'enviroment' => '.example.env'
];
