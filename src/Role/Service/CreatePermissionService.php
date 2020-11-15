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
use Ares\Role\Entity\Permission;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\PermissionRepository;
use DateTime;

/**
 * Class CreatePermissionService
 *
 * @package Ares\Role\Service
 */
class CreatePermissionService
{
    /**
     * CreatePermissionService constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        private PermissionRepository $permissionRepository
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
        /** @var Permission $existingPermission */
        $existingPermission = $this->permissionRepository->get($data['name'], 'name', true);

        if ($existingPermission) {
            throw new RoleException(__('Permission %s already exists', [$existingPermission->getName()]));
        }

        $permission = $this->getNewPermission($data);

        /** @var Permission $permission */
        $permission = $this->permissionRepository->save($permission);

        return response()
            ->setData($permission);
    }

    /**
     * @param array $data
     *
     * @return Permission
     */
    private function getNewPermission(array $data): Permission
    {
        $permission = new Permission();

        $permission
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setCreatedAt(new DateTime());

        return $permission;
    }
}
