<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Role\Entity\Contract\RoleHierarchyInterface;

/**
 * Class RoleHierarchy
 *
 * @package Ares\Role\Entity
 */
class RoleHierarchy extends DataObject implements RoleHierarchyInterface
{
    /** @var string */
    public const TABLE = 'ares_roles_hierarchy';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(RoleHierarchyInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return RoleHierarchy
     */
    public function setId(int $id): RoleHierarchy
    {
        return $this->setData(RoleHierarchyInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getParentRoleId(): int
    {
        return $this->getData(RoleHierarchyInterface::COLUMN_PARENT_ROLE_ID);
    }

    /**
     * @param int $parent_role_id
     *
     * @return RoleHierarchy
     */
    public function setParentRoleId(int $parent_role_id): RoleHierarchy
    {
        return $this->setData(RoleHierarchyInterface::COLUMN_PARENT_ROLE_ID, $parent_role_id);
    }

    /**
     * @return int
     */
    public function getChildRoleId(): int
    {
        return $this->getData(RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID);
    }

    /**
     * @param int $child_role_id
     *
     * @return RoleHierarchy
     */
    public function setChildRoleId(int $child_role_id): RoleHierarchy
    {
        return $this->setData(RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID, $child_role_id);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(RoleHierarchyInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $created_at
     *
     * @return RoleHierarchy
     */
    public function setCreatedAt(\DateTime $created_at): RoleHierarchy
    {
        return $this->setData(RoleHierarchyInterface::COLUMN_CREATED_AT, $created_at);
    }
}
