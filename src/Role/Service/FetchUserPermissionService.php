<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RoleUserRepository;

/**
 * Class FetchUserPermissionService
 *
 * @package Ares\Role\Service
 */
class FetchUserPermissionService
{
    /**
     * FetchUserPermissionService constructor.
     *
     * @param RoleUserRepository      $roleUserRepository
     * @param RoleHierarchyRepository $roleHierarchyRepository
     */
    public function __construct(
        private RoleUserRepository $roleUserRepository,
        private RoleHierarchyRepository $roleHierarchyRepository
    ) {}

    /**
     * @param int $userId
     *
     * @return CustomResponseInterface
     */
    public function execute(int $userId): CustomResponseInterface
    {
        /** @var array $userRoleIds */
        $userRoleIds = $this->roleUserRepository->getUserRoleIds($userId);

        if (!$userRoleIds) {
            return response()
                ->setData([]);
        }

        /** @var array $allRoleIds */
        $allRoleIds = $this->roleHierarchyRepository
            ->getAllRoleIdsHierarchy($userRoleIds);

        /** @var array $permissions */
        $permissions = $this->roleUserRepository->getUserPermissions($allRoleIds);

        return response()
            ->setData($permissions);
    }
}
