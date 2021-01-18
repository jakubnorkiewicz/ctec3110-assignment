<?php
/**
 * Configuration file used by Phinx migration tool.
 */

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

return [
    'paths' => [
        'migrations' => 'includes/migrations'
    ],
    'migration_base_class' => '\App\Migration',
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => 'mysql',
            'host' => $_ENV['DB_HOST_15'],
            'name' => $_ENV['DB_NAME_15'],
            'user' => $_ENV['DB_USER_15'],
            'pass' => $_ENV['DB_PASSWORD_15'],
            'port' => $_ENV['DB_PORT_15']
        ]
    ]
];
