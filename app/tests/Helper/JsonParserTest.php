<?php

declare(strict_types=1);

namespace Tests\Helper;

use App\Helper\JsonParser;
use App\Interface\ParserInterface;
use stdClass;
use Tests\TestCase;

/**
 * @internal
 *
 * @small
 */
final class JsonParserTest extends TestCase
{
    private ParserInterface $parser;

    protected function setUp(): void
    {
        $this->parser = new JsonParser();
    }

    public function testRequestsWithDataParseAsExpected(): void
    {
        $data = ['foo' => 1, 'bar' => 2];
        $request = $this->createRequest('POST', '/')->withParsedBody($data);
        $parsed = $this->parser->parse($request);

        $this->assertSame($data, $parsed);
    }

    public function testRequestWithObjectDataParsesAsExpected(): void
    {
        $data = new stdClass();
        $data->foo = 1;
        $data->bar = 2;

        $request = $this->createRequest('POST', '/')->withParsedBody($data);
        $parsed = $this->parser->parse($request);

        $this->assertSame(get_object_vars($data), $parsed);
    }

    public function testRequestWithoutABodyReturnsEmptyArray(): void
    {
        $request = $this->createRequest('POST', '/');
        $parsed = $this->parser->parse($request);

        $this->assertSame([], $parsed);
    }

    public function testInvalidDataReturnsEmptyArray(): void
    {
        $parsed = $this->parser->parse(1);

        $this->assertSame([], $parsed);
    }
}
