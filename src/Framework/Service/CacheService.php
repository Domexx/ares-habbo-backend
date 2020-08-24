<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Framework\Service;

use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Phpfastcache\Helper\Psr16Adapter as FastCache;
use Psr\Cache\InvalidArgumentException;

/**
 * Class CacheService
 *
 * @package Ares\Framework\Service
 */
class CacheService
{
    /**
     * @var FastCache
     */
    private FastCache $fastCache;

    /**
     * CacheService constructor.
     *
     * @param FastCache $fastCache
     */
    public function __construct(
        FastCache $fastCache
    ) {
        $this->fastCache = $fastCache;
    }

    /**
     * @param   string  $key
     *
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function has(string $key): bool
    {
        $this->isCacheEnabled();

        return $this->fastCache->has($key);
    }

    /**
     * @param   string  $key
     *
     * @return  mixed
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function get(string $key)
    {
        if (!$this->has($key) || !$this->isCacheEnabled()) {
            return null;
        }

        return $this->fastCache->get($key);
    }

    /**
     * @param   array  $keys
     *
     * @return string
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function getMultiple(array $keys): string
    {
        $this->isCacheEnabled();

        return $this->fastCache->getMultiple($keys);
    }

    /**
     * @param   string  $key
     * @param           $value
     * @param   int     $ttl
     *
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        $this->isCacheEnabled();

        return $this->fastCache->set($key, $value, $this->getTTL($ttl));
    }

    /**
     * @param   array  $values
     * @param   int    $ttl
     *
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function setMultiple(array $values, int $ttl = 0): bool
    {
        $this->isCacheEnabled();

        return $this->fastCache->setMultiple($values, $this->getTTL($ttl));
    }

    /**
     * @param   string  $key
     *
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function delete(string $key): bool
    {
        $this->isCacheEnabled();

        return $this->fastCache->delete($key);
    }

    /**
     * @param   array  $keys
     *
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function deleteMultiple(array $keys): bool
    {
        $this->isCacheEnabled();

        return $this->fastCache->deleteMultiple($keys);
    }

    /**
     * @return bool
     * @throws PhpfastcacheSimpleCacheException
     */
    public function clear(): bool
    {
        $this->isCacheEnabled();

        return $this->fastCache->clear();
    }

    /**
     * @param   int  $ttl
     *
     * @return int|mixed
     */
    private function getTTL(int $ttl)
    {
        if (!$ttl) {
            $ttl = $_ENV['CACHE_TTL'];
        }

        return $ttl;
    }

    /**
     * Check whether Caching is Enabled or not
     *
     * @return bool
     */
    private function isCacheEnabled(): bool
    {
        if (!$_ENV['CACHE_ENABLED']) {
            return false;
        }

        return true;
    }
}
