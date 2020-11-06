<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Entity\Permission;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RolePermission;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Repository\RoleRepository;

/**
 * Class CreateChildPermission
 *
 * @package Ares\Role\Service
 */
class CreateRolePermissionService
{
    /**
     * @var RolePermissionRepository
     */
    private RolePermissionRepository $rolePermissionRepository;

    /**
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * @var RoleRepository
     */
    private RoleRepository $roleRepository;

    /**
     * CreateChildPermission constructor.
     *
     * @param RolePermissionRepository $rolePermissionRepository
     * @param PermissionRepository     $permissionRepository
     * @param RoleRepository           $roleRepository
     */
    public function __construct(
        RolePermissionRepository $rolePermissionRepository,
        PermissionRepository $permissionRepository,
        RoleRepository $roleRepository
    ) {
        $this->rolePermissionRepository = $rolePermissionRepository;
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $roleId */
        $roleId = $data['role_id'];

        /** @var int $permissionId */
        $permissionId = $data['permission_id'];

        /** @var Role $role */
        $role = $this->roleRepository->get($roleId);

        /** @var Permission $permission */
        $permission = $this->permissionRepository->get($permissionId);

        if (!$role || !$permission) {
            throw new RoleException(__('Could not found given Role or Permission'));
        }

        /** @var RolePermission $existingRolePermission */
        $existingRolePermission = $this->rolePermissionRepository
            ->getExistingRolePermission(
                $role->getId(),
                $permission->getId()
            );

        if ($existingRolePermission) {
            throw new RoleException(__('There is already a Permission assigned to that Role'));
        }

        $rolePermission = $this->getNewRolePermission($role->getId(), $permission->getId());

        /** @var RolePermission $rolePermission */
        $rolePermission = $this->rolePermissionRepository->save($rolePermission);

        return response()
            ->setData($rolePermission);
    }

    /**
     * @param int $roleId
     * @param int $permissionId
     *
     * @return RolePermission
     */
    private function getNewRolePermission(int $roleId, int $permissionId): RolePermission
    {
        $rolePermission = new RolePermission();

        $rolePermission
            ->setRoleId($roleId)
            ->setPermissionId($permissionId);

        return $rolePermission;
    }
}
