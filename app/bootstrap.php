<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE.md (GNU License)
 */

use Psr\Container\ContainerInterface;
use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = new Dotenv\Dotenv(__DIR__ . '/../');
if (file_exists(__DIR__ . '/../' . '.env')) {
    $dotEnv->load();
}

// Instantiate LeagueContainer
$container = new \League\Container\Container();

// Enable Autowiring for our dependencies..
$container->delegate(
    new \League\Container\ReflectionContainer()
);

// Parse our providers
require_once __DIR__ . '/providers.php';

// Create App instance
$app = $container->get(App::class);;

$middlewares = require_once __DIR__ . '/middlewares.php';
$middlewares($app);

// Routing
$routes = require __DIR__ . '/routes.php';
$routes($app);

return $app;
