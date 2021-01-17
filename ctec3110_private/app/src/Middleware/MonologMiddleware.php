<?php

namespace App\Middleware;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

final class MonologMiddleware implements MiddlewareInterface
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $log = new Logger('web-activity');
        $log->pushHandler(new StreamHandler(__DIR__ . '/../../../../ctec3110_private/storage/log/web-activity.log',
            Logger::INFO));

        $log->info($request->getProtocolVersion() . ' ' . $request->getMethod() . ' ' . $request->getUri());

        return $handler->handle($request);
    }
}
