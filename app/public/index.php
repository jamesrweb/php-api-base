<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use App\AppFactory;

$app = AppFactory::create();
$app->run();