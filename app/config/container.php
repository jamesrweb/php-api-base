<?php

declare(strict_types=1);

use function DI\create;
use function DI\factory;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\PsrLogMessageProcessor;
use Monolog\Processor\UidProcessor;
use Monolog\Processor\WebProcessor;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\CallableResolverInterface;
use Slim\Middleware\ErrorMiddleware;
use Slim\Psr7\Factory\ResponseFactory;

return [
    ResponseFactoryInterface::class => create(ResponseFactory::class),
    ErrorMiddleware::class => factory(static function (ContainerInterface $container) {
        return new ErrorMiddleware(
            callableResolver: $container->get(CallableResolverInterface::class),
            responseFactory: $container->get(ResponseFactoryInterface::class),
            displayErrorDetails: $_ENV['ENVIRONMENT'] === 'development',
            logErrors: true,
            logErrorDetails: true,
            logger: $container->get(LoggerInterface::class)
        );
    }),
    LoggerInterface::class => factory(static function (ContainerInterface $container) {
        $environment = $_ENV['ENVIRONMENT'];
        $formatter = $container->get(JsonFormatter::class);
        $handler = new StreamHandler(dirname(__DIR__)."/var/log/app-{$environment}.log");
        $handler->setFormatter($formatter);

        return new Logger('app_logger', [$handler], [
            $container->get(MemoryUsageProcessor::class),
            $container->get(PsrLogMessageProcessor::class),
            $container->get(WebProcessor::class),
            $container->get(IntrospectionProcessor::class),
            $container->get(UidProcessor::class),
            $container->get(MemoryPeakUsageProcessor::class),
        ]);
    }),
];
