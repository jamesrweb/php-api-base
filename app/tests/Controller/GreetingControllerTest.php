<?php

declare(strict_types=1);

namespace Tests\Controller;

use Lukasoppermann\Httpstatus\Httpstatuscodes as Status;
use function Safe\json_decode;
use Slim\App;
use Tests\TestCase;

/**
 * @internal
 *
 * @small
 */
final class GreetingControllerTest extends TestCase
{
    private App $app;

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
    }

    public function testValidGetRequest(): void
    {
        $request = $this->createRequest('GET', '/v1/greet/User');
        $response = $this->app->handle($request);

        $expected = ['message' => 'Hello, User!'];
        $actual = json_decode((string) $response->getBody(), true);

        $this->assertSame(Status::HTTP_OK, $response->getStatusCode());
        $this->assertSame($expected, $actual);
    }

    public function testValidPostRequest(): void
    {
        $data = ['name' => 'User'];
        $request = $this->createRequest('POST', '/v1/greet')->withParsedBody($data);
        $response = $this->app->handle($request);

        $expected = ['message' => 'Hello, User!'];
        $actual = json_decode((string) $response->getBody(), true);

        $this->assertSame(Status::HTTP_OK, $response->getStatusCode());
        $this->assertSame($expected, $actual);
    }

    public function testInvalidPostRequestContainingNoData(): void
    {
        $data = [];
        $request = $this->createRequest('POST', '/v1/greet')->withParsedBody($data);
        $response = $this->app->handle($request);

        $payload = json_decode((string) $response->getBody(), true);

        $this->assertSame(Status::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $payload);
    }

    public function testInvalidPostRequestContainingEmptyNameValue(): void
    {
        $data = ['name' => ''];
        $request = $this->createRequest('POST', '/v1/greet')->withParsedBody($data);
        $response = $this->app->handle($request);

        $payload = json_decode((string) $response->getBody(), true);

        $this->assertSame(Status::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertArrayHasKey('error', $payload);
    }

    public function testPreflightRequestToGreetPostRouteReturnsCorrectly(): void
    {
        $request = $this->createRequest('OPTIONS', '/v1/greet');
        $response = $this->app->handle($request);

        $this->assertSame('POST, OPTIONS', $response->getHeaderLine('Access-Control-Allow-Methods'));
    }

    public function testPreflightRequestToGreetGetRouteReturnsCorrectly(): void
    {
        $request = $this->createRequest('OPTIONS', '/v1/greet/User');
        $response = $this->app->handle($request);

        $this->assertSame('GET, OPTIONS', $response->getHeaderLine('Access-Control-Allow-Methods'));
    }
}
