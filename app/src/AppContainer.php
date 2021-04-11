<?php

declare(strict_types=1);

namespace App;

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

final class AppContainer
{
    public static function create(): ContainerInterface
    {
        $builder = new ContainerBuilder();
        $definitions = require dirname(__DIR__).'/config/container.php';

        return $builder->addDefinitions($definitions)->build();
    }
}
