<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Provider;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Logger;
use PHLAK\Config\Config;

/**
 * Class ConfigServiceProvider
 *
 * @package Ares\Framework\Provider
 */
class ConfigServiceProvider extends AbstractServiceProvider
{
    protected $provides = [
        'settings',
        Config::class
    ];

    public function register()
    {
        $container = $this->getContainer();

        $container->add(Config::class, function () {
            return new Config('../app/Configs');
        });

        $container->add('settings', function () {
            return [
                'doctrine' => [
                    'dev_mode' => $_ENV['DB_DEV_MODE'],

                    'cache_dir' => base_dir() . 'tmp/Cache/doctrine',

                    'proxy_dir' => base_dir() . 'tmp/Cache/proxies',

                    'metadata_dirs' => ['src'],

                    'connection' => [
                        'driver' => 'pdo_mysql',
                        'host' => $_ENV['DB_HOST'],
                        'port' => $_ENV['DB_PORT'],
                        'dbname' => $_ENV['DB_NAME'],
                        'user' => $_ENV['DB_USER'],
                        'password' => $_ENV['DB_PASSWORD'],
                        'charset' => 'utf8mb4'
                    ]
                ],

                'cache' => [
                    'enabled' => $_ENV['CACHE_ENABLED'],
                    'type' => $_ENV['CACHE_TYPE'],
                    'ttl' => $_ENV['CACHE_TTL'],
                    'redis_host' => $_ENV['CACHE_REDIS_HOST'],
                    'redis_port' => $_ENV['CACHE_REDIS_PORT']
                ],

                'logger' => [
                    'name' => $_ENV['WEB_NAME'] . '-event-log',
                    'enabled_log_levels' => [
                        // DEBUG
                        [
                            'path' => base_dir() . 'tmp/Logs/info.log',
                            'level' => Logger::DEBUG
                        ],
                        // INFO
                        [
                            'path' => base_dir() . 'tmp/Logs/info.log',
                            'level' => Logger::INFO
                        ],
                        // NOTICE
                        [
                            'path' => base_dir() . 'tmp/Logs/info.log',
                            'level' => Logger::NOTICE
                        ],
                        // WARNING
                        [
                            'path' => base_dir() . 'tmp/Logs/warning.log',
                            'level' => Logger::WARNING
                        ],
                        // ERROR
                        [
                            'path' => base_dir() . 'tmp/Logs/error.log',
                            'level' => Logger::ERROR
                        ],
                        // CRITICAL
                        [
                            'path' => base_dir() . 'tmp/Logs/critical.log',
                            'level' => Logger::CRITICAL
                        ],
                        // ALERT
                        [
                            'path' => base_dir() . 'tmp/Logs/critical.log',
                            'level' => Logger::ALERT
                        ],
                        // EMERGENCY
                        [
                            'path' => base_dir() . 'tmp/Logs/critical.log',
                            'level' => Logger::EMERGENCY
                        ],
                    ],
                ]
            ];
        });
    }
}
