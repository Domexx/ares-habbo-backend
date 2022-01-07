<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Repository\RoleRankRepository;

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
     * @param RoleRankRepository      $roleRankRepository
     * @param RoleHierarchyRepository $roleHierarchyRepository
     */
    public function __construct(
        private RoleRankRepository $roleRankRepository,
        private RolePermissionRepository $rolePermissionRepository,
        private RoleHierarchyRepository $roleHierarchyRepository
    ) {}

    /**
     * @param int $userId
     *
     * @return CustomResponseInterface
     */
    public function execute(int $rankId): CustomResponseInterface
    {
        /** @var int $roleId */
        $roleId = $this->roleRankRepository->getRoleId($rankId);
        
        if (!$roleId) {
            return response()->setData([]);
        }

        /** @var array $permissions */
        $permissions = $this->rolePermissionRepository->getRolePermissions($roleId);

        return response()->setData($permissions);
    }
}