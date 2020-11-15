<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserBadge;
use Ares\Framework\Model\Query\Collection;

/**
 * Class UserBadgeRepository
 *
 * @package Ares\User\Repository
 */
class UserBadgeRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_USER_BADGE_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_BADGE_COLLECTION_';

    /** @var string */
    protected string $entity = UserBadge::class;

    /**
     * @param int $profileId
     *
     * @return Collection
     */
    public function getListOfSlottedUserBadges(int $profileId): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'user_id' => $profileId
            ])->where('slot_id', '>', 1)
            ->orderBy('slot_id', 'ASC');

        return $this->getList($searchCriteria);
    }

    /**
     * @param int $profileId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedBadgeList(int $profileId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('user_id', $profileId)
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
