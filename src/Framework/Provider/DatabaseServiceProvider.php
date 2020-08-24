<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Provider;

use Doctrine\Common\Annotations\CachedReader;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Proxy\Autoloader;
use Doctrine\ORM\Tools\Setup;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

/**
 * Class DatabaseServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class DatabaseServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        EntityManager::class
    ];

    public function register()
    {

        $container = $this->getContainer();
        $container->add(EntityManager::class, function () use ($container) {

            $settings = $container->get('settings');
            $cacheDriver = new \Doctrine\Common\Cache\FilesystemCache(
                base_dir() . 'tmp/Cache/filecache/doctrine'
            );

            // Annotation drivers
            $config = Setup::createAnnotationMetadataConfiguration(
                $settings['doctrine']['metadata_dirs'],
                $settings['doctrine']['dev_mode']
            );
            $config->setMetadataDriverImpl(
                new AnnotationDriver(
                    new CachedReader(
                        new AnnotationReader,
                        $cacheDriver
                    ),
                    $settings['doctrine']['metadata_dirs']
                )
            );
            // Sets our Proxy Directory
            $config->setProxyDir($settings['doctrine']['proxy_dir']);
            $config->setProxyNamespace('Ares\Framework\Proxies');

            Autoloader::register($settings['doctrine']['proxy_dir'], 'Ares\Framework\Proxies');

            // Set our Cache
            if ($_ENV['API_DEBUG'] == "production") {
                $config->setResultCacheImpl($cacheDriver);
                $config->setQueryCacheImpl($cacheDriver);
                $config->setMetadataCacheImpl($cacheDriver);
                $config->setAutoGenerateProxyClasses(false);
            } else {
                $config->setAutoGenerateProxyClasses(true);
            }

            return EntityManager::create($settings['doctrine']['connection'], $config);
        });
    }
}
