<?php

declare(strict_types=1);

namespace Tests\Middleware;

use Tests\TestCase;

/**
 * @internal
 *
 * @small
 */
final class CORSMiddlewareTest extends TestCase
{
    public function testCorsHeadersArePresent(): void
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/v1/greet/User');
        $response = $app->handle($request);

        $this->assertNotEmpty($response->getHeaderLine('Access-Control-Allow-Origin'));
        $this->assertNotEmpty($response->getHeaderLine('Access-Control-Allow-Methods'));
        $this->assertNotEmpty($response->getHeaderLine('Access-Control-Allow-Headers'));
        $this->assertNotEmpty($response->getHeaderLine('Access-Control-Allow-Credentials'));
    }
}
