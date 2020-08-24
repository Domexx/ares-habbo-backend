<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/app/bootstrap.php';

$container = $app->getContainer();
$em = $container->get(EntityManager::class);

return ConsoleRunner::createHelperSet($em);
