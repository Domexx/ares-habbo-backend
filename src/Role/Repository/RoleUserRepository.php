<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Exception\NoSuchEntityException;
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
    public function getUserRoleIds(int $userId): ?array
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
     * @throws NoSuchEntityException
     */
    public function getUserAssignedRole(int $roleId, int $userId): ?RoleUser
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'role_id' => $roleId,
                'user_id' => $userId
            ]);

        return $this->getOneBy($searchCriteria, true);
    }

    /**
     * @param array $allUserRoleIds
     *
     * @return array|null
     */
    public function getUserPermissions(array $allUserRoleIds): ?array
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'ares_roles_permission.id',
                'ares_roles_permission.role_id',
                'ares_roles_permission.permission_id'
            ])->from('ares_roles_permission')
            ->leftJoin(
                'ares_permissions',
                'ares_permissions.id',
                '=',
                'ares_roles_permission.permission_id'
            )->whereIn(
                'ares_roles_permission.role_id',
                $allUserRoleIds
            )->select('ares_permissions.name');

        return array_values(
            array_unique(
                $this->getList($searchCriteria)->get('name') ?? []
            )
        );
    }
}
