<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function Safe\json_encode;
use function Safe\sprintf;
use Slim\Exception\HttpException;

final class HTTPExceptionMiddleware implements MiddlewareInterface
{
    public function __construct(private ResponseFactoryInterface $responseFactory)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (HttpException $exception) {
            $code = $exception->getCode();
            $response = $this->responseFactory->createResponse()->withStatus($code);
            $error = json_encode(['error' => sprintf('%s %s', $code, $response->getReasonPhrase())]);

            $response->getBody()->write($error);

            return $response;
        }
    }
}
