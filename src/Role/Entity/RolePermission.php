<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Role\Entity\Contract\RolePermissionInterface;

/**
 * Class RolePermission
 *
 * @package Ares\Role\Entity
 */
class RolePermission extends DataObject implements RolePermissionInterface
{
    /** @var string */
    public const TABLE = 'ares_roles_permission';

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
     * @param int $role_id
     *
     * @return RolePermission
     */
    public function setRoleId(int $role_id): RolePermission
    {
        return $this->setData(RolePermissionInterface::COLUMN_ROLE_ID, $role_id);
    }

    /**
     * @return int
     */
    public function getPermissionId(): int
    {
        return $this->getData(RolePermissionInterface::COLUMN_PERMISSION_ID);
    }

    /**
     * @param int $permission_id
     *
     * @return RolePermission
     */
    public function setPermissionId(int $permission_id): RolePermission
    {
        return $this->setData(RolePermissionInterface::COLUMN_PERMISSION_ID, $permission_id);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(RolePermissionInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $created_at
     *
     * @return RolePermission
     */
    public function setCreatedAt(\DateTime $created_at): RolePermission
    {
        return $this->setData(RolePermissionInterface::COLUMN_CREATED_AT, $created_at);
    }
}
