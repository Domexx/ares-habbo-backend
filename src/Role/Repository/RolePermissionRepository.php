<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\Contract\RolePermissionInterface;
use Ares\Role\Entity\RolePermission;

/**
 * Class RolePermissionRepository
 *
 * @package Ares\Role\Repository
 */
class RolePermissionRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_ROLE_PERMISSION_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_ROLE_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = RolePermission::class;

    /**
     * @param int $permissionId
     * @param array $roleIds
     *
     * @return bool
     */
    public function isPermissionAssigned(int $permissionId, array $roleIds): bool
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select('id')
            ->where('permission_id', $permissionId)
            ->whereIn('role_id', $roleIds)
            ->limit(1);

        $result = $this->getList($searchCriteria)->toArray();

        return count($result) > 0;
    }

    /**
     * @param int $roleId
     * @param int $userId
     *
     * @return RoleUser|null
     * @throws NoSuchEntityException
     */
    public function getPermissionAssignedRole(int $roleId, int $permissionId): ?RolePermission
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'role_id' => $roleId,
                'permission_id' => $permissionId
            ]);

        return $this->getOneBy($searchCriteria, true, false);
    }

    /**
     * @param int $roleId
     *
     * @return Collection|null
     */
    public function getRolePermissions(int $roleId): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('permission')
            ->where([
                'role_id' => $roleId
            ]);

        return $this->getList($searchCriteria, false);
    }

    /**
     * @param int $permissionId
     * 
     *@return Collection|null
     */
    public function getRolesWithPermissionId(int $permissionId, bool $isCached = true) : Collection 
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where(RolePermissionInterface::COLUMN_PERMISSION_ID, $permissionId);

        return $this->getList($searchCriteria, $isCached);
    }
}
