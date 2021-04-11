<?php

declare(strict_types=1);

namespace App\Helper;

use App\Interface\ParserInterface;
use Psr\Http\Message\ServerRequestInterface;

class JsonParser implements ParserInterface
{
    /**
     * @return array<string, mixed>
     */
    public function parse(mixed $request): array
    {
        if (is_a($request, ServerRequestInterface::class) === false) {
            return [];
        }

        /** @var array<string, mixed>|object|null $data */
        $data = $request->getParsedBody();

        if (is_null($data)) {
            return [];
        }

        if (is_object($data)) {
            return get_object_vars($data);
        }

        return $data;
    }
}
