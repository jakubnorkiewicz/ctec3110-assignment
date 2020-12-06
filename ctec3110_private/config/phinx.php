<?php
/**
 * Configuration file used by Phinx migration tool.
 */
require './ctec3110_private/config.php';
return [
    'paths' => [
        'migrations' => 'ctec3110_private/migrations'
    ],
    'migration_base_class' => '\App\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => 'mysql',
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'pass' => DB_PASSWORD,
            'port' => DB_PORT
        ]
    ]
];
