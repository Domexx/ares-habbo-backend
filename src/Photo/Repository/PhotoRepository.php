<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Photo\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Photo\Entity\Photo;

/**
 * Class PhotoRepository
 *
 * @package Ares\Photo\Repository
 */
class PhotoRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_PHOTO_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_PHOTO_COLLECTION_';

    /** @var string */
    protected string $entity = Photo::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedPhotoList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $userId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedUserPhotoList(int $userId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
