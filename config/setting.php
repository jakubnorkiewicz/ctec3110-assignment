<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production

        // PHP Renderer settings
        'renderer' => [
            'template_path' => '../components/',
        ],

        // Twig View settings
        'view' => [
            'template_path' => '../components/',
            'cache_path' => '../storage/cache/',
            'cache' => false,
            'debug' => true,
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => '../storage/log/server.log',
        ],
    ],
];
