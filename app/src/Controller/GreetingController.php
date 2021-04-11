<?php

declare(strict_types=1);

namespace App\Controller;

use App\Helper\JsonParser;
use Lukasoppermann\Httpstatus\Httpstatuscodes as Status;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use function Safe\json_encode;

final class GreetingController
{
    public function get(ResponseInterface $response, string $name): ResponseInterface
    {
        $data = json_encode(['message' => "Hello, {$name}!"]);
        $response->getBody()->write($data);

        return $response->withStatus(Status::HTTP_OK);
    }

    public function post(ServerRequestInterface $request, ResponseInterface $response, LoggerInterface $logger, JsonParser $parser): ResponseInterface
    {
        $body = $parser->parse($request);

        if (array_key_exists('name', $body) === false) {
            $error = "The 'name' key must exist in the request body.";
            $logger->debug($error);
            $response->getBody()->write(json_encode(['error' => $error]));

            return $response->withStatus(Status::HTTP_BAD_REQUEST);
        }

        $name = $body['name'];

        if ($name === '') {
            $error = "The 'name' key must have at least 1 character.";
            $logger->debug($error);
            $response->getBody()->write(json_encode(['error' => $error]));

            return $response->withStatus(Status::HTTP_BAD_REQUEST);
        }

        $data = json_encode(['message' => "Hello, {$name}!"]);
        $response->getBody()->write($data);

        return $response->withStatus(Status::HTTP_OK);
    }
}
