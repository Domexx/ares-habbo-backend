<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Repository;


use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Rank\Entity\Rank;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleRank;
use Ares\Role\Entity\Contract\RoleRankInterface;
use Illuminate\Database\QueryException;

/**
 * Class RoleRankRepository
 *
 * @package Ares\Role\Repository
 */
class RoleRankRepository extends BaseRepository {

    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_RANK_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_RANK_COLLECTION_';

    /** @var string */
    protected string $entity = RoleRank::class;

    /**
     * @param int $rankId
     *
     * @return RoleRank|null
     */
    public function getRoleRankByRankId(int $rankId) : ?RoleRank {
        $searchCriteria = $this->getDataObjectManager()
        ->where(RoleRankInterface::COLUMN_RANK_ID, $rankId);

        return $this->getOneBy($searchCriteria, true);
    }

    /**
     * @param int $roleId
     *
     * @return RoleRank|null
     */
    public function getRoleRankByRoleId(int $roleId) : ?RoleRank {
        $searchCriteria = $this->getDataObjectManager()
        ->where(RoleRankInterface::COLUMN_ROLE_ID, $roleId);

        return $this->getOneBy($searchCriteria, true);
    }

    /**
     * //TODO Don't depend on database, move to cache
     * 
     * @param int $roleId
     * @param int $rankId
     *
     * @return RoleRank|null
     * @throws NoSuchEntityException
     */
    public function getRankAssignedRole(int $roleId, int $rankId): ?RoleRank {
        $searchCriteria = $this->getDataObjectManager()
        ->where(RoleRankInterface::COLUMN_ROLE_ID, '=', $roleId)
        ->orWhere(RoleRankInterface::COLUMN_RANK_ID, '=', $rankId);
        
        return $this->getOneBy($searchCriteria, true, false);
    }
}