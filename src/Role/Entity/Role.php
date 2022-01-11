<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Permission\Entity\Permission as Rank;
use Ares\Permission\Repository\PermissionRepository;
use Ares\Role\Entity\Contract\RoleInterface;
use Ares\Role\Entity\Contract\RoleRankInterface;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Repository\RoleRepository;

/**
 * Class Role
 *
 * @package Ares\Role\Entity
 */
class Role extends DataObject implements RoleInterface
{
    /** @var string */
    public const TABLE = 'ares_roles';

    /** @var array **/
    public const RELATIONS = [
        'permission' => 'getPermission',
        'permissionWithUsers' => 'getPermissionWithUsers',
        'rolePermissions' => 'getRolePermissions'
    ];

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
     * @return int
     */
    public function getIsRoot(): int
    {
        return $this->getData(RoleInterface::COLUMN_IS_ROOT);
    }

    /**
     * @param int $isRoot
     *
     * @return Role
     */
    public function setIsRoot(int $isRoot): Role
    {
        return $this->setData(RoleInterface::COLUMN_IS_ROOT, $isRoot);
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

    /**
    *
    * @return array|null
    */
    public function getRolePermissions() {
        $permissions = $this->getData('role_permissions');

        if($permissions) {
            return $permissions;
        }

        if(!isset($this)) {
            return null;
        }

        /** @var RolePermissionRepository $rolePermissionRepository */
        $rolePermissionRepository = repository(RolePermissionRepository::class);

        /** @var Collection $permissions */
        $permissions = $rolePermissionRepository->getRolePermissions($this->getId());

        $this->setRolePermissions($permissions);

        return $permissions;
    }

    /**
     * @param mixed $permissions
     *
     * @return Role
    */
    public function setRolePermissions(mixed $permissions): Role
    {
        return $this->setData('role_permissions', $permissions);
    }

    /**
     * //TODO Add $isCached flag in Ares-core
     * 
     * @return Rank|null
     *
     * @throws DataObjectManagerException
     */
    public function getPermission(bool $appendUsers = false, $isCached = true): ?Rank
    {
        if(!isset($this)) {
            return null;
        }

        /** @var Rank $rank */
        $rank = $this->getData('permission');

        if ($rank && $isCached) {
            if($appendUsers) {
                $rank->getUsers($isCached);
            }

            return $rank;
        }

        /** @var RoleRepository $roleRepository */
        $roleRepository = repository(RoleRepository::class);

        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = repository(PermissionRepository::class);

        /** @var Rank $rank */
        $rank = $roleRepository->getManyToMany(
            $permissionRepository, 
            $this->getId(), 
            'ares_roles_rank', 
            RoleRankInterface::COLUMN_ROLE_ID,
            RoleRankInterface::COLUMN_RANK_ID,
            $isCached
        )->first();

        if(!$rank) {
            return null;
        }

        if($appendUsers) {
            $rank->getUsers($isCached);
        }

        $this->setPermission($rank);

        return $rank;
    }

     /**
     * @return Rank|null
     *
     * @throws DataObjectManagerException
     */
    public function getPermissionWithUsers($isCached = true): ?Rank
    {
        /** @var Rank $rank */
        $rank = $this->getPermission(true, $isCached);

        return $rank;
    }

    /**
     * @param Rank $rank
     *
     * @return Role
     */
    public function setPermission(Rank $rank): Role
    {
        return $this->setData('permission', $rank);
    }
}
