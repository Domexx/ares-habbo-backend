<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
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
     * @param int $parentRoleId
     *
     * @return RoleHierarchy
     */
    public function setParentRoleId(int $parentRoleId): RoleHierarchy
    {
        return $this->setData(RoleHierarchyInterface::COLUMN_PARENT_ROLE_ID, $parentRoleId);
    }

    /**
     * @return int
     */
    public function getChildRoleId(): int
    {
        return $this->getData(RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID);
    }

    /**
     * @param int $childRoleId
     *
     * @return RoleHierarchy
     */
    public function setChildRoleId(int $childRoleId): RoleHierarchy
    {
        return $this->setData(RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID, $childRoleId);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(RoleHierarchyInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return RoleHierarchy
     */
    public function setCreatedAt(\DateTime $createdAt): RoleHierarchy
    {
        return $this->setData(RoleHierarchyInterface::COLUMN_CREATED_AT, $createdAt);
    }
}
