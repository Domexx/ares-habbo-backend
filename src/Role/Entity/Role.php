<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Role\Entity\Contract\RoleInterface;

/**
 * Class Role
 *
 * @package Ares\Role\Entity
 */
class Role extends DataObject implements RoleInterface
{
    /** @var string */
    public const TABLE = 'ares_roles';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(RoleInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Role
     */
    public function setId(int $id): Role
    {
        return $this->setData(RoleInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(RoleInterface::COLUMN_NAME);
    }

    /**
     * @param string $name
     *
     * @return Role
     */
    public function setName(string $name): Role
    {
        return $this->setData(RoleInterface::COLUMN_NAME, $name);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(RoleInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Role
     */
    public function setDescription(string $description): Role
    {
        return $this->setData(RoleInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->getData(RoleInterface::COLUMN_STATUS);
    }

    /**
     * @param int $status
     *
     * @return Role
     */
    public function setStatus(int $status): Role
    {
        return $this->setData(RoleInterface::COLUMN_STATUS, $status);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(RoleInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Role
     */
    public function setCreatedAt(\DateTime $createdAt): Role
    {
        return $this->setData(RoleInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(RoleInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Role
     */
    public function setUpdatedAt(\DateTime $updatedAt): Role
    {
        return $this->setData(RoleInterface::COLUMN_UPDATED_AT, $updatedAt);
    }
}
