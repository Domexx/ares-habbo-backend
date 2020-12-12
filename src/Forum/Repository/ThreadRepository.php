<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Repository;

use Ares\Forum\Entity\Thread;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;

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
     * @return Thread|null
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function getSingleThread(int $topicId, string $slug): ?Thread
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('user')
            ->where([
                'topic_id' => $topicId,
                'slug' => $slug
            ]);

        /** @var Thread $thread */
        return $this->getOneBy($searchCriteria);
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

    /**
     * @param int    $userId
     * @param int    $topicId
     * @param string $slug
     *
     * @return Thread|null
     * @throws NoSuchEntityException
     */
    public function getExistingThread(int $userId, int $topicId, string $slug): ?Thread
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'user_id' => $userId,
                'topic_id' => $topicId,
                'slug' => $slug
            ]);

        /** @var Thread $thread */
        return $this->getOneBy($searchCriteria);
    }
}
