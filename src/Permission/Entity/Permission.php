<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Permission\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Permission\Entity\Contract\PermissionInterface;
use Ares\Permission\Repository\PermissionRepository;
use Ares\User\Repository\UserRepository;
use Ares\Framework\Model\Query\Collection;
use Ares\Role\Entity\Role;
use Ares\Role\Repository\RoleRepository;

/**
 * Class Permission
 *
 * @package Ares\Permission\Entity
 */
class Permission extends DataObject implements PermissionInterface
{
    /** @var string */
    public const TABLE = 'permissions';

    /** @var array **/
    public const RELATIONS = [
      'users' => 'getUsers',
      'role' => 'getRole'
    ];

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
    public function getRankName(): string
    {
        return $this->getData(PermissionInterface::COLUMN_RANK_NAME);
    }

    /**
     * @param string $rankName
     *
     * @return Permission
     */
    public function setRankName(string $rankName): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_RANK_NAME, $rankName);
    }

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->getData(PermissionInterface::COLUMN_BADGE);
    }

    /**
     * @param string $badge
     *
     * @return Permission
     */
    public function setBadge(string $badge): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_BADGE, $badge);
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->getData(PermissionInterface::COLUMN_LEVEL);
    }

    /**
     * @param int $level
     *
     * @return Permission
     */
    public function setLevel(int $level): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_LEVEL, $level);
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->getData(PermissionInterface::COLUMN_PREFIX);
    }

    /**
     * @param string $prefix
     *
     * @return Permission
     */
    public function setPrefix(string $prefix): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_PREFIX, $prefix);
    }

    /**
     * @return string
     */
    public function getPrefixColor(): string
    {
        return $this->getData(PermissionInterface::COLUMN_PREFIX_COLOR);
    }

    /**
     * @param string $prefixColor
     *
     * @return Permission
     */
    public function setPrefixColor(string $prefixColor): Permission
    {
        return $this->setData(PermissionInterface::COLUMN_PREFIX_COLOR, $prefixColor);
    }

    /**
     * @return Collection|null
     *
     * @throws DataObjectManagerException
     */
    public function getUsers(): ?Collection
    {
        $users = $this->getData('users');

        if ($users) {
            return $users;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = repository(PermissionRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        $users = $permissionRepository->getOneToMany(
            $userRepository,
            $this->getId(),
            'rank'
        );

        if (!$users->toArray()) {
            return null;
        }

        $this->setUsers($users);

        return $users;

    }

    /**
     * @param Collection $users
     *
     * @return Permission
     */
    public function setUsers(Collection $users): Permission
    {
        return $this->setData('users', $users);
    }

    /**
     * @return Role|null
     *
     * @throws DataObjectManagerException
     */
    public function getRole(): ?Role
    {
        $role = $this->getData('role');

        if ($role) {
            return $role;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var RoleRepository $roleRepository */
        $roleRepository = repository(RoleRepository::class);

        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = repository(PermissionRepository::class);

        /** @var Role $role */
        $role = $permissionRepository->getManyToMany(
            $roleRepository, 
            $this->getId(), 
            'ares_roles_rank', 
            'rank_id',
            'role_id'
        )->first();

        if(!$role) {
            return null;
        }

        $this->setRole($role);

        return $role;
    }

    /**
     * @param Role $role
     *
     * @return Permission
     */
    public function setRole(Role $role): Permission
    {
        return $this->setData('role', $role);
    }
}
