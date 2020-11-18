<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/register', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Register page");
    return $response;
});