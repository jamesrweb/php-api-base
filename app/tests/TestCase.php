<?php

declare(strict_types=1);

namespace Tests;

use App\AppFactory;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use function Safe\fopen;
use Slim\App;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

/**
 * @internal
 *
 * @small
 */
class TestCase extends PHPUnitTestCase
{
    protected function getAppInstance(): App
    {
        return AppFactory::create();
    }

    /**
     * Create a mock HTTP request.
     *
     * @param array<string, string> $request_headers The request headers of the request
     * @param array<string, string> $cookies         The request cookies collection
     * @param array<string, string> $server_params   The server environment variables
     */
    protected function createRequest(
        string $method,
        string $path,
        array $request_headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $server_params = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);
        $headers = new Headers();

        foreach ($request_headers as $name => $value) {
            $headers->addHeader($name, $value);
        }

        return new Request($method, $uri, $headers, $cookies, $server_params, $stream);
    }
}
