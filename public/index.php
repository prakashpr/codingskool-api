<?php
declare(strict_types=1);

use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__. '/../src/Models/db.php';

$app = AppFactory::create();

// Register routes
$routes = require __DIR__ . '/../app/routes.php';
$routes($app);

$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, false);

$app->run();