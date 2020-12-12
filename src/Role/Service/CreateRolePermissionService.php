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
use Ares\Role\Entity\Permission;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RolePermission;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
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
     * CreateChildPermission constructor.
     *
     * @param RolePermissionRepository $rolePermissionRepository
     * @param PermissionRepository     $permissionRepository
     * @param RoleRepository           $roleRepository
     */
    public function __construct(
        private RolePermissionRepository $rolePermissionRepository,
        private PermissionRepository $permissionRepository,
        private RoleRepository $roleRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws NoSuchEntityException
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

        /** @var RolePermission $existingRolePermission */
        $existingRolePermission = $this->rolePermissionRepository
            ->getExistingRolePermission(
                $role->getId(),
                $permission->getId()
            );

        if ($existingRolePermission) {
            throw new RoleException(
                __('There is already a Permission assigned to that Role'),
                RoleResponseCodeInterface::RESPONSE_ROLE_PERMISSION_ALREADY_ASSIGNED_TO_ROLE,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
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
            ->setPermissionId($permissionId)
            ->setCreatedAt(new \DateTime());

        return $rolePermission;
    }
}
