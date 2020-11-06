<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserBadge;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * @param int $page
     * @param int $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedBadgeList(int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
