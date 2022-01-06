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
use Ares\Role\Entity\Contract\RolePermissionInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\RolePermissionRepository;

/**
 * Class DeleteRolePermissionsService
 *
 * @package Ares\Role\Service
 */
class DeleteRolePermissionsService
{
    /**
     * DeleteRolePermissionsService constructor.
     *
     * @param RolePermissionRepository $rolePermissionRepository
     */
    public function __construct(
        private RolePermissionRepository $rolePermissionRepository
    ) {}

    /**
     * @param int $id
     *
     * @return CustomResponseInterface
     * @throws RoleException
     * @throws DataObjectManagerException
     */
    public function execute(int $id): CustomResponseInterface
    {
        /** @var int $roleId */
        $roleId = $id;

        $this->rolePermissionRepository
            ->getDataObjectManager()
            ->where(RolePermissionInterface::COLUMN_ROLE_ID, $roleId)
            ->delete();

        return response()
            ->setData(true);
    }
}
