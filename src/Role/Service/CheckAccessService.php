<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Role\Entity\Permission;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Repository\RoleUserRepository;

/**
 * Class CheckAccessService
 *
 * @package Ares\Role\Service
 */
class CheckAccessService
{
    /**
     * CheckAccessService constructor.
     *
     * @param PermissionRepository     $permissionRepository
     * @param RoleUserRepository       $roleUserRepository
     * @param RoleHierarchyRepository  $roleHierarchyRepository
     * @param RolePermissionRepository $rolePermissionRepository
     */
    public function __construct(
        private PermissionRepository $permissionRepository,
        private RoleUserRepository $roleUserRepository,
        private RoleHierarchyRepository $roleHierarchyRepository,
        private RolePermissionRepository $rolePermissionRepository
    ) {}

    /**
     * @param int         $userId
     * @param string|null $permissionName
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, ?string $permissionName): bool
    {
        /** @var Permission $permission */
        $permission = $this->permissionRepository->get($permissionName, 'name', true);

        // When there's no permission set, set anonymous(logged in) access
        if (!$permission) {
            return true;
        }

        $roleIds = $this->roleUserRepository->getUserRoleIds($userId);

        if ($roleIds && count($roleIds) > 0) {
            $allRoleIds = $this->roleHierarchyRepository
                ->getAllRoleIdsHierarchy($roleIds);

            return $this->rolePermissionRepository
                ->isPermissionAssigned(
                    $permission->getId(),
                    $allRoleIds
                );
        }

        return false;
    }
}
