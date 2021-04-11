<?php

declare(strict_types=1);

namespace Tests\Middleware;

use Lukasoppermann\Httpstatus\Httpstatuscodes as Status;
use function Safe\json_decode;
use Tests\TestCase;

/**
 * @internal
 *
 * @small
 */
final class HTTPExceptionMiddlewareTest extends TestCase
{
    public function testCatchesHttp404Exception(): void
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('GET', '/unknown');
        $response = $app->handle($request);

        $payload = json_decode((string) $response->getBody(), true);

        $this->assertSame(Status::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertArrayHasKey('error', $payload);
        $this->assertStringContainsString((string) Status::HTTP_NOT_FOUND, $payload['error']);
    }
}
