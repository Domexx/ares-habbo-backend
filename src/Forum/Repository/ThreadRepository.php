<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Repository;

use Ares\Forum\Entity\Thread;
use Ares\Framework\Repository\BaseRepository;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;

/**
 * Class ThreadRepository
 *
 * @package Ares\Forum\Repository
 */
class ThreadRepository extends BaseRepository
{
    /** @var string */
    protected const CACHE_PREFIX = 'ARES_FORUM_THREAD_';

    /** @var string */
    protected const CACHE_COLLECTION_PREFIX = 'ARES_FORUM_THREAD_COLLECTION_';

    /** @var string */
    protected string $entity = Thread::class;

    /**
     * @param int    $topic
     * @param string $slug
     * @param bool   $cachedEntity
     *
     * @return mixed|object|null
     * @throws PhpfastcacheSimpleCacheException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function findByCriteria(int $topic, string $slug, bool $cachedEntity = true)
    {
        $entity = $this->cacheService->get(self::CACHE_PREFIX . $slug);

        if ($entity && $cachedEntity) {
            return unserialize($entity);
        }

        $entity = $this->findOneBy([
            'topic' => $topic,
            'slug' => $slug
        ]);

        $this->cacheService->set(self::CACHE_PREFIX . $slug, serialize($entity));

        return $entity;
    }
}