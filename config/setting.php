<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // PHP Renderer settings
        'renderer' => [
            'template_path' => PATH_ROOT . '../components/',
        ],

        // Twig View settings
        'view' => [
            'template_path' => PATH_ROOT . '../components/',
            'cache_enabled' => false, // in production set to true
            'cache_path' => PATH_ROOT . '../storage/cache/',
            'debug' => true,
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => PATH_ROOT . '../storage/log/server.log',
        ],
    ],
];
