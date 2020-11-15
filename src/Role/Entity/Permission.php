<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Role\Entity\Contract\PermissionInterface;

/**
 * Class Permission
 *
 * @package Ares\Role\Entity
 */
class Permission extends DataObject implements PermissionInterface
{
    /** @var string */
    public const TABLE = 'ares_permissions';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(PermissionInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Permission
     */
    public function setId(int $id): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(PermissionInterface::COLUMN_NAME);
    }

    /**
     * @param string $name
     *
     * @return Permission
     */
    public function setName(string $name): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_NAME, $name);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(PermissionInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Permission
     */
    public function setDescription(string $description): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->getData(PermissionInterface::COLUMN_STATUS);
    }

    /**
     * @param int $status
     *
     * @return Permission
     */
    public function setStatus(int $status): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_STATUS, $status);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(PermissionInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Permission
     */
    public function setCreatedAt(\DateTime $createdAt): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(PermissionInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Permission
     */
    public function setUpdatedAt(\DateTime $updatedAt): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_UPDATED_AT, $updatedAt);
    }
}
