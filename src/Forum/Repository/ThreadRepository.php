<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Repository;

use Ares\Forum\Entity\Thread;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedThreadList(int $topicId, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('topic_id', (int) $topicId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
