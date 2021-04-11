<?php

declare(strict_types=1);

namespace App\Middleware;

use Lukasoppermann\Httpstatus\Httpstatuscodes as Status;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function Safe\preg_match;

final class RouteNormalisationMiddleware implements MiddlewareInterface
{
    public function __construct(private ResponseFactoryInterface $responseFactory)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri = $request->getUri();
        $path = $uri->getPath();

        if ($path !== '/' && preg_match('/\\/$/', $path) === 1) {
            $path = rtrim($path, '/');
            $uri = $uri->withPath($path);

            if ($request->getMethod() === 'GET') {
                return $this->responseFactory
                    ->createResponse()
                    ->withStatus(Status::HTTP_MOVED_PERMANENTLY)
                    ->withHeader('Location', (string) $uri);
            }

            return $handler->handle($request->withUri($uri))->withStatus(Status::HTTP_PERMANENT_REDIRECT);
        }

        return $handler->handle($request);
    }
}
