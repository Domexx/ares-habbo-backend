<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Repository;


use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Permission\Entity\Permission as Rank;
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
     * @return int|null
     */
    public function getRoleId(int $rankId) : ?int {

        $searchCriteria = $this->getDataObjectManager()
        ->where('rank_id', $rankId);

        /** @var RoleRank $roleRank */
        $roleRank = $this->getOneBy($searchCriteria, true);

        if(!$roleRank) {
            return null;
        }

        return $roleRank->getRoleId();
    }

    /**
     * @param int $roleId
     *
     * @return array|null
     * @throws QueryException
     */
    public function getRoleRankIds(int $roleId) : ?array {
        $searchCriteria = $this->getDataObjectManager()
        ->select('rank_id')
        ->where('role_id', $roleId);
        //TODO this must be only for one
        return $this->getList($searchCriteria)->get('rank_id');
    }

    /**
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
        //TODO I dont like this...
        return $this->getOneBy($searchCriteria, true, false);
    }
}