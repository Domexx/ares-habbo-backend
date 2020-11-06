<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

if ($_ENV['CACHE_ENABLED']) {
    $container->addServiceProvider(
        new \Ares\Framework\Provider\CacheServiceProvider()
    );
}

if(!file_exists(tmp_dir())) {
    mkdir(tmp_dir(), 0755, true);
}

// Sets our Route-Cache
if ($_ENV['API_DEBUG'] == "production") {
    $routeCollector = $app->getRouteCollector();

    if(!file_exists(route_cache_dir())) {
        mkdir(route_cache_dir(), 0755, true);
    }

    $routeCollector->setCacheFile(route_cache_dir() . '/route.cache.php');
}