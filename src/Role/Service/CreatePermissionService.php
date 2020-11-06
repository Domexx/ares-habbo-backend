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
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\PermissionRepository;

/**
 * Class CreatePermissionService
 *
 * @package Ares\Role\Service
 */
class CreatePermissionService
{
    /**
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * CreatePermissionService constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        PermissionRepository $permissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
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
        $searchCriteria = $this->permissionRepository
            ->getDataObjectManager()
            ->where('name', $data['name']);

        /** @var Permission $existingPermission */
        $existingPermission = $this->permissionRepository->get($data['name'], 'name');

        if ($existingPermission) {
            throw new RoleException(__('There is already a Permission with that name'));
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
            ->setDescription($data['description']);

        return $permission;
    }
}
