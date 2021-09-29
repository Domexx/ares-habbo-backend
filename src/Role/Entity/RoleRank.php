<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */
namespace Ares\Role\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Role\Entity\Contract\RoleRankInterface;
use Ares\Permission\Repository\PermissionRepository;
use Ares\Permission\Entity\Permission;
use DateTime;

/**
 * Class RoleRank
 *
 * @package Ares\Role\Entity
 */
class RoleRank extends DataObject implements RoleRankInterface
{
    /** @var string */
    public const TABLE = 'ares_roles_rank';

    /**
     * @return int
     */
    public function getId() : int {
        return $this->getData(RoleRankInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return RoleRank
     */
    public function setId(int $id) : RoleRank {
        return $this->setData(RoleRankInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getRoleId() : int {
        return $this->getData(RoleRankInterface::COLUMN_ROLE_ID);
    }

    /**
     * @param int $role_id
     *
     * @return RoleRank
     */
    public function setRoleId(int $roleId) : RoleRank {
        return $this->setData(RoleRankInterface::COLUMN_ROLE_ID, $roleId);
    }

    /**
     * @return int
     */
    public function getRankId(): int
    {
        return $this->getData(RoleRankInterface::COLUMN_RANK_ID);
    }

    /**
     * @param int $rankId
     *
     * @return RoleRank
     */
    public function setRankId(int $rankId): RoleRank
    {
        return $this->setData(RoleRankInterface::COLUMN_RANK_ID, $rankId);
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->getData(RoleRankInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param DateTime $createdAt
     *
     * @return RoleRank
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        return $this->setData(RoleRankInterface::COLUMN_CREATED_AT, $createdAt);
    }
}