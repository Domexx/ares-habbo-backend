<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\Permission;
use Ares\Role\Entity\RolePermission;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Repository\RoleRepository;
use DateTime;

/**
 * Class DeleteRolePermissionService
 *
 * @package Ares\Role\Service
 */
class ToggleRolePermissionService
{
    /**
     * ToggleRolePermissionService constructor.
     *
     * @param RolePermissionRepository $rolePermissionRepository
     */
    public function __construct(
        private RoleRepository $roleRepository,
        private PermissionRepository $permissionRepository,
        private RolePermissionRepository $rolePermissionRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function execute(int $roleId, int $permissionId): CustomResponseInterface
    {
        /** @var Role $role */
        $role = $this->roleRepository->get($roleId);

        /** @var Permission $permission */
        $permission = $this->permissionRepository->get($permissionId);

        /** @var RolePermission $isPermissionAlreadyAssigned */
        $isPermissionAlreadyAssigned = $this->rolePermissionRepository
        ->getPermissionAssignedRole(
            $role->getId(),
            $permission->getId()
        );

        if ($isPermissionAlreadyAssigned) {
            $this->rolePermissionRepository->delete($isPermissionAlreadyAssigned->getId());

            return response()->setData(true);
        }

        $rolePermission = $this->getNewRolePermission($role->getId(), $permission->getId());

        $this->rolePermissionRepository->save($rolePermission);

        return response()->setData(true);
    }

    /**
     * @param int $roleId
     * @param int $userId
     *
     * @return RolePermission
     */
    private function getNewRolePermission(int $roleId, int $permissionId): RolePermission
    {
        $rolePermission = new RolePermission();

        $rolePermission
            ->setRoleId($roleId)
            ->setPermissionId($permissionId)
            ->setCreatedAt(new DateTime());

        return $rolePermission;
    }
}
