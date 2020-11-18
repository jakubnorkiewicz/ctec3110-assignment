<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/sms-form', function (Request $request, Response $response, $args) {
    $response->getBody()->write("SMS form");
    return $response;
});