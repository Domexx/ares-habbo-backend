<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Provider;

use Phpfastcache\Config\ConfigurationOption;
use Phpfastcache\Drivers\Predis\Config as PredisConfig;
use Phpfastcache\Helper\Psr16Adapter as FastCache;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class CacheServiceProvider
 * 
 * @package Ares\Framework\Provider
 */
class CacheServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        FastCache::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->share(FastCache::class, function () use ($container) {
            $settings = $container->get('settings');

            if ($settings['cache']['type'] == 'Predis') {
                $configurationOption = new PredisConfig([
                    'host' => $settings['cache']['redis_host'],
                    'port' => (int)$settings['cache']['redis_port']
                ]);

                return new FastCache($_ENV['CACHE_TYPE'], $configurationOption);
            }

            $configurationOption = new ConfigurationOption([
                'path' => cache_dir() . '/filecache'
            ]);

            return new FastCache($_ENV['CACHE_TYPE'], $configurationOption);
        });
    }
}
