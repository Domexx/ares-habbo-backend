<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleRank;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Repository\RoleRankRepository;
use Ares\Permission\Repository\PermissionRepository;
use Ares\Role\Repository\RoleHierarchyRepository;

/**
 * Class RoleTreeService
 *
 * @package Ares\Role\Service
 */
class RoleTreeService {
    /**
     * RoleTreeService constructor.
     *
     * @param RoleRankRepository    $roleRankRepository
     * @param RoleRepository        $roleRepository
     * @param PermissionRepository  $permissionRepository
     */
    public function __construct(
        private RoleRankRepository $roleRankRepository,
        private RoleRepository $roleRepository,
        private RoleHierarchyRepository $roleHierarchyRepository,
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * @param int $userId
     *
     * @return array
    */
    public function getRoleTree() {
        $rootRole = $this->roleRepository->getRootRole();

        if($rootRole == null) {
            //throw error no root set.
            return null;
        }

        $rootRole->children = $this->getRoleChildren($rootRole);

        foreach($rootRole->children as $group) {
            $group->children = $this->getRoleChildren($group);
        }

        return $rootRole;
    }

    /**
    * @param Role $role
    *
    * @return array
    */
    public function getRoleChildren(Role $role) : array {
        $roleChildrenIds = $this->roleHierarchyRepository->getChildIds([$role->getId()]);
        $roleChildren = [];

        foreach($roleChildrenIds as $roleChildId) {
            /** @var Role $roleChild */
            $roleChild = $this->roleRepository->get($roleChildId);

            $roleChild->getRolePermissions();
            $roleChild->getPermissionWithUsers();

            if($roleChild != null) {
                array_push($roleChildren, $roleChild);
            }
        }

        return $roleChildren;
    }
}