<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

if (!function_exists('__')) {
    /**
     * Takes message and placeholder to translate them to global locale.
     *
     * @param string $key
     * @param array $placeholder
     * @return mixed
     */
    function __(string $key, array $placeholder = []) {
        $container = \Ares\Framework\Proxy\App::getContainer();
        $localeService = $container->get(\Ares\Framework\Service\LocaleService::class);
        return $localeService->translate($key, $placeholder);
    }
}

if (!function_exists('response')) {
    /**
     * Returns instance of custom response.
     *
     * @return \Ares\Framework\Interfaces\CustomResponseInterface
     */
    function response(): \Ares\Framework\Interfaces\CustomResponseInterface {
        $container = \Ares\Framework\Proxy\App::getContainer();
        return $container->get(\Ares\Framework\Model\CustomResponse::class);
    }
}

if (!function_exists('app_dir')) {
    /**
     * Returns directory path of app.
     *
     * @return string
     */
    function app_dir() {
        return __DIR__;
    }
}

if (!function_exists('base_dir')) {
    /**
     * Returns directory path of root.
     *
     * @return string
     */
    function base_dir() {
        return __DIR__ . '/../';
    }
}

if (!function_exists('cache_dir')) {
    /**
     * Returns directory path of cache.
     *
     * @return string
     */
    function cache_dir() {
        return __DIR__ . '/../tmp/Cache';
    }
}

if (!function_exists('route_cache_dir')) {
    /**
     * Returns directory path of routing cache.
     *
     * @return string
     */
    function route_cache_dir() {
        return __DIR__ . '/../tmp/Cache/routing';
    }
}
