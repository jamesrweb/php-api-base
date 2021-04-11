<?php

declare(strict_types=1);

namespace App;

use App\Action\PreflightAction;
use App\Controller\GreetingController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Symfony\Component\Dotenv\Dotenv;

$env = dirname(__DIR__).'/.env';
if (file_exists($env)) {
    (new Dotenv())->load($env);
}

final class AppFactory
{
    public static function create(): App
    {
        $container = AppContainer::create();
        $builder = new AppBuilder($container);
        $app = $builder
            ->withCorsHeaders()
            ->withBodyParsing()
            ->withRouting()
            ->withContentLengthHeaders()
            ->withRouteNormalisation()
            ->withHttpExceptionResponses()
            ->withErrorMiddleware()
            ->build();

        $app->group('/v1', static function (RouteCollectorProxyInterface $group): void {
            $group->get('/greet/{name}', [GreetingController::class, 'get']);
            $group->options('/greet/{name}', PreflightAction::class);

            $group->post('/greet', [GreetingController::class, 'post']);
            $group->options('/greet', PreflightAction::class);
        });

        return $app;
    }
}
