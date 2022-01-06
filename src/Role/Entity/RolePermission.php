<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Role\Entity\Permission;
use Ares\Role\Entity\Contract\RolePermissionInterface;
use Ares\Role\Repository\PermissionRepository;

/**
 * Class RolePermission
 *
 * @package Ares\Role\Entity
 */
class RolePermission extends DataObject implements RolePermissionInterface
{
    /** @var string */
    public const TABLE = 'ares_roles_permission';

    /** @var array **/
    public const RELATIONS = [
        'permission' => 'getPermission'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(RolePermissionInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return RolePermission
     */
    public function setId(int $id): RolePermission
    {
        return $this->setData(RolePermissionInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->getData(RolePermissionInterface::COLUMN_ROLE_ID);
    }

    /**
     * @param int $roleId
     *
     * @return RolePermission
     */
    public function setRoleId(int $roleId): RolePermission
    {
        return $this->setData(RolePermissionInterface::COLUMN_ROLE_ID, $roleId);
    }

    /**
     * @return int
     */
    public function getPermissionId(): int
    {
        return $this->getData(RolePermissionInterface::COLUMN_PERMISSION_ID);
    }

    /**
     * @param int $permissionId
     *
     * @return RolePermission
     */
    public function setPermissionId(int $permissionId): RolePermission
    {
        return $this->setData(RolePermissionInterface::COLUMN_PERMISSION_ID, $permissionId);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(RolePermissionInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return RolePermission
     */
    public function setCreatedAt(\DateTime $createdAt): RolePermission
    {
        return $this->setData(RolePermissionInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
    * @return Permission|null
    *
    * @throws DataObjectManagerException
    */
    public function getPermission(): ?Permission
    {
        /** @var Permission $permission */
        $permission = $this->getData('permission');

        if ($permission) {
            return $permission;
        }
        
        if(!isset($this)) {
            return null;
        }

        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = repository(PermissionRepository::class);

        $permission = $permissionRepository->getPermissionById($this->getPermissionId());

        $this->setPermission($permission);

        return $permission;
    }

    /**
     * @param Permission $permissions
     *
     * @return RolePermission
    */
    public function setPermission(Permission $permission) : RolePermission {
        return $this->setData('permission', $permission);
    }
}
