<?php

declare(strict_types=1);

namespace App\Interface;

interface ParserInterface
{
    public function parse(mixed $value): mixed;
}
