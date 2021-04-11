<?php

declare(strict_types=1);

namespace Tests\Action;

use Lukasoppermann\Httpstatus\Httpstatuscodes as Status;
use Tests\TestCase;

/**
 * @internal
 *
 * @small
 */
final class PreflightActionTest extends TestCase
{
    public function testPreflightingARoute(): void
    {
        $app = $this->getAppInstance();
        $request = $this->createRequest('OPTIONS', '/v1/greet/User');
        $response = $app->handle($request);

        $this->assertNotEmpty($response->getHeaderLine('Access-Control-Allow-Methods'));
        $this->assertNotEmpty($response->getHeaderLine('Allow'));
        $this->assertSame(Status::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
