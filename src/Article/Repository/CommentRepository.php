<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Repository;

use Ares\Article\Entity\Comment;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class CommentRepository
 *
 * @package Ares\Article\Repository
 */
class CommentRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_COMMENT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_COMMENT_COLLECTION_';

    /** @var string */
    protected string $entity = Comment::class;

    /**
     * @param int $articleId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedCommentList(int $articleId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('article_id', $articleId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
