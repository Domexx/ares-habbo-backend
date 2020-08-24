<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

/**
 * Registers our ServiceProviders
 */
return [
    // Adds our LocaleProvider to add locales
    $container->addServiceProvider(
        new \Ares\Framework\Provider\LocaleServiceProvider()
    ),
    // Adds our ConfigProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\ConfigServiceProvider()
    ),
    // Adds our DatabaseProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\DatabaseServiceProvider()
    ),
    // Adds our LoggingProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\LoggingServiceProvider()
    ),
    // Adds our RouteProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\RouteServiceProvider()
    ),
    // Adds our AppProvider and creates App
    $container->addServiceProvider(
        new \Ares\Framework\Provider\AppServiceProvider()
    ),
    // Adds our ValidationProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\ValidationServiceProvider()
    ),
    // Adds our CacheServiceProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\CacheServiceProvider()
    ),
    // Adds our SearchCriteriaServiceProvider
    $container->addServiceProvider(
        new \Ares\Framework\Provider\SearchCriteriaServiceProvider()
    )
];
