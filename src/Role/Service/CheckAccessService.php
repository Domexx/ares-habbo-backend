<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Role\Entity\Contract\PermissionInterface;
use Ares\Role\Entity\Permission;
use Ares\Role\Entity\RoleRank;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Repository\RoleRankRepository;
use Ares\User\Repository\UserRepository;

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
     * @param RoleRankRepository       $roleRankRepository
     * @param RoleHierarchyRepository  $roleHierarchyRepository
     * @param RolePermissionRepository $rolePermissionRepository
     * @param UserRepository           $userRepository
     */
    public function __construct(
        private PermissionRepository $permissionRepository,
        private RoleRankRepository $roleRankRepository,
        private RoleHierarchyRepository $roleHierarchyRepository,
        private RolePermissionRepository $rolePermissionRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @param int         $userId
     * @param string|null $permissionName
     *
     * @return bool
     * @throws NoSuchEntityException
     */
    public function execute(int $userRank, ?string $permissionName): bool
    {
        /** @var Permission $permission */
        $permission = $this->permissionRepository->get($permissionName, PermissionInterface::COLUMN_NAME, true);
        
        // When there's no permission set, set anonymous(logged in) access
        if (!$permission) {
            return true;
        }

        /** @var RoleRank $roleRank */
        $roleRank = $this->roleRankRepository->getRoleRankByRankId($userRank);

        if ($roleRank) {
            $allRoleIds = $this->roleHierarchyRepository->getAllRoleIdsHierarchy([$$roleRank->getRankId()]);

            return $this->rolePermissionRepository->isPermissionAssigned($permission->getId(), $allRoleIds);
        }

        return false;
    }
}