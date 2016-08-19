<?php

/**
 * Wizzy configuration file
 */
return [
    /*
     * Application system requirements
     */
    'system_requirements' => [
        'php' => [
            'minimum_stability' => '5.5.9',
            'preferred_stability' => '5.5.9'
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
        'server_requirements' => true,
        'environment' => true,
        'database' => true,
    ],
    /*
     * Wizzy route group prefix
     */
    'prefix' => 'install',
    'enviroment'
];
