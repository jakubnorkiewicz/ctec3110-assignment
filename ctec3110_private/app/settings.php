<?php
/**
 * General config file for the SLIM application.
 */

$wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
define('WSDL', $wsdl);

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
