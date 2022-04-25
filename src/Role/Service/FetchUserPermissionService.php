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
use Ares\Role\Entity\RoleRank;

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
        /** @var RoleRank $roleRank */
        $roleRank = $this->roleRankRepository->getRoleRankByRankId($rankId);
        
        if (!$roleRank) {
            return response()->setData([]);
        }

        /** @var array $permissions */
        $permissions = $this->rolePermissionRepository->getRolePermissions($roleRank->getRankId());

        return response()->setData($permissions);
    }
}