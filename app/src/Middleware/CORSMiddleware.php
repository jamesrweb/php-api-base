<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteContext;

final class CORSMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $context = RouteContext::fromRequest($request);
        $results = $context->getRoutingResults();
        $methods = $results->getAllowedMethods();
        $headers = $request->getHeaderLine('Access-Control-Request-Headers');
        $response = $handler->handle($request);

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', implode(', ', $methods))
            ->withHeader('Access-Control-Allow-Headers', $headers === '' ? '*' : $headers)
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}
