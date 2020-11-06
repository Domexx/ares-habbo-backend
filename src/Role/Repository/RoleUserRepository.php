<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\RoleUser;
use Illuminate\Database\QueryException;

/**
 * Class RoleUserRepository
 *
 * @package Ares\Role\Repository
 */
class RoleUserRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_USER_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_USER_COLLECTION_';

    /** @var string */
    protected string $entity = RoleUser::class;

    /**
     * @param int $userId
     *
     * @return array|null
     * @throws QueryException
     */
    public function getUserRoleIds($userId): ?array
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select('role_id')
            ->where('user_id', $userId);

        return $this->getList($searchCriteria)->get('role_id');
    }

    /**
     * @param int $roleId
     * @param int $userId
     *
     * @return RoleUser|null
     */
    public function getUserAssignedRole(int $roleId, int $userId): ?RoleUser
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'role_id' => $roleId,
                'user_id' => $userId
            ]);

        return $this->getList($searchCriteria)->first();
    }
}
