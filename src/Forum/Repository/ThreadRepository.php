<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Repository;

use Ares\Forum\Entity\Thread;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Framework\Model\Query\Collection;

/**
 * Class ThreadRepository
 *
 * @package Ares\Forum\Repository
 */
class ThreadRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_FORUM_THREAD_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_FORUM_THREAD_COLLECTION_';

    /** @var string */
    protected string $entity = Thread::class;

    /**
     * @param int    $topicId
     * @param string $slug
     *
     * @return Collection
     * @throws DataObjectManagerException
     */
    public function getSingleThread(int $topicId, string $slug): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('user')
            ->where([
                'topic_id' => $topicId,
                'slug' => $slug
            ]);

        /** @var Thread $thread */
        return $this->getList($searchCriteria)->first();
    }

    /**
     * @param int $topicId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedThreadList(int $topicId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('topic_id', $topicId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
