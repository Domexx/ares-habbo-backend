<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Repository;

use Ares\Forum\Entity\Topic;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class TopicRepository
 *
 * @package Ares\Forum\Repository
 */
class TopicRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_FORUM_TOPIC_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_FORUM_TOPIC_COLLECTION_';

    /** @var string */
    protected string $entity = Topic::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedTopicList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
