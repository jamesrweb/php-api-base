<?php

declare(strict_types=1);

namespace Tests\Middleware;

use Lukasoppermann\Httpstatus\Httpstatuscodes as Status;
use Tests\TestCase;

/**
 * @internal
 *
 * @small
 */
final class RouteNormalisationMiddlewareTest extends TestCase
{
    public function testGetRequestsWithTrailingSlashPassThrough(): void
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/v1/greet/User/');
        $response = $app->handle($request);

        $this->assertSame(Status::HTTP_MOVED_PERMANENTLY, $response->getStatusCode());
        $this->assertStringEndsWith('/v1/greet/User', $response->getHeaderLine('Location'));
    }

    public function testPostRequestsWithTrailingSlashPassThrough(): void
    {
        $data = ['name' => 'User'];
        $app = $this->getAppInstance();
        $request = $this->createRequest('POST', '/v1/greet/')->withParsedBody($data);
        $response = $app->handle($request);

        $this->assertSame(Status::HTTP_PERMANENT_REDIRECT, $response->getStatusCode());
    }
}
