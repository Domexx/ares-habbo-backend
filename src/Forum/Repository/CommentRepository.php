<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Repository;

use Ares\Forum\Entity\Comment;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class CommentRepository
 *
 * @package Ares\Forum\Repository
 */
class CommentRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_FORUM_COMMENT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_FORUM_COMMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Comment::class;

    /**
     * @param int $threadId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedThreadCommentList(int $threadId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('thread_id', $threadId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
