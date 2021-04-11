<?php

declare(strict_types=1);

namespace App\Action;

use Lukasoppermann\Httpstatus\Httpstatuscodes as Status;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;

final class PreflightAction
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $context = RouteContext::fromRequest($request);
        $results = $context->getRoutingResults();
        $methods = $results->getAllowedMethods();

        return $response
            ->withHeader('Allow', implode(', ', $methods))
            ->withStatus(Status::HTTP_NO_CONTENT);
    }
}
