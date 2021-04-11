<?php

declare(strict_types=1);

namespace App;

use App\Interface\BuilderInterface;
use App\Middleware\CORSMiddleware;
use App\Middleware\HTTPExceptionMiddleware;
use App\Middleware\RouteNormalisationMiddleware;
use DI\Bridge\Slim\Bridge;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Middleware\ContentLengthMiddleware;
use Slim\Middleware\ErrorMiddleware;

final class AppBuilder implements BuilderInterface
{
    /**
     * @var array<string>
     */
    private array $middlewares = [];
    private App $app;

    public function __construct(private ContainerInterface $container)
    {
        $this->app = Bridge::create($container);
    }

    public function withBodyParsing(): self
    {
        $this->middlewares[] = 'addBodyParsingMiddleware';

        return $this;
    }

    public function withRouting(): self
    {
        $this->middlewares[] = 'addRoutingMiddleware';

        return $this;
    }

    public function withCorsHeaders(): self
    {
        $this->middlewares[] = CORSMiddleware::class;

        return $this;
    }

    public function withContentLengthHeaders(): self
    {
        $this->middlewares[] = ContentLengthMiddleware::class;

        return $this;
    }

    public function withRouteNormalisation(): self
    {
        $this->middlewares[] = RouteNormalisationMiddleware::class;

        return $this;
    }

    public function withHttpExceptionResponses(): self
    {
        $this->middlewares[] = HTTPExceptionMiddleware::class;

        return $this;
    }

    public function withErrorMiddleware(): self
    {
        $this->middlewares[] = ErrorMiddleware::class;

        return $this;
    }

    public function build(): App
    {
        return array_reduce($this->middlewares, [$this, 'build_reducer'], $this->app);
    }

    private function build_reducer(App $app, string $middleware): App
    {
        $callable = [$app, $middleware];

        if (is_callable($callable)) {
            call_user_func($callable);

            return $app;
        }

        return $this->apply($app, $middleware);
    }

    private function apply(App $app, string $middleware): App
    {
        $middleware = $this->container->get($middleware);

        return $app->add($middleware);
    }
}
