<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rank\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Rank\Entity\Contract\RankInterface;
use Ares\Rank\Repository\RankRepository;
use Ares\User\Repository\UserRepository;
use Ares\Framework\Model\Query\Collection;
use Ares\Role\Entity\Role;
use Ares\Role\Repository\RoleRepository;

/**
 * Class Rank
 *
 * @package Ares\Rank\Entity
 */
class Rank extends DataObject implements RankInterface
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
        return $this->getData(RankInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Rank
     */
    public function setId(int $id): Rank
    {
        return $this->setData(RankInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getRankName(): string
    {
        return $this->getData(RankInterface::COLUMN_RANK_NAME);
    }

    /**
     * @param string $rankName
     *
     * @return Rank
     */
    public function setRankName(string $rankName): Rank
    {
        return $this->setData(RankInterface::COLUMN_RANK_NAME, $rankName);
    }

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->getData(RankInterface::COLUMN_BADGE);
    }

    /**
     * @param string $badge
     *
     * @return Rank
     */
    public function setBadge(string $badge): Rank
    {
        return $this->setData(RankInterface::COLUMN_BADGE, $badge);
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->getData(RankInterface::COLUMN_LEVEL);
    }

    /**
     * @param int $level
     *
     * @return Rank
     */
    public function setLevel(int $level): Rank
    {
        return $this->setData(RankInterface::COLUMN_LEVEL, $level);
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->getData(RankInterface::COLUMN_PREFIX);
    }

    /**
     * @param string $prefix
     *
     * @return Rank
     */
    public function setPrefix(string $prefix): Rank
    {
        return $this->setData(RankInterface::COLUMN_PREFIX, $prefix);
    }

    /**
     * @return string
     */
    public function getPrefixColor(): string
    {
        return $this->getData(RankInterface::COLUMN_PREFIX_COLOR);
    }

    /**
     * @param string $prefixColor
     *
     * @return Rank
     */
    public function setPrefixColor(string $prefixColor): Rank
    {
        return $this->setData(RankInterface::COLUMN_PREFIX_COLOR, $prefixColor);
    }

    /**
     * @return Collection|null
     *
     * @throws DataObjectManagerException
     */
    public function getUsers($isCached = true): ?Collection
    {
        if (!isset($this)) {
            return null;
        }

        $users = $this->getData('users');

        if ($users && $isCached) {
            return $users;
        }

        /** @var RankRepository $rankRepository */
        $rankRepository = repository(RankRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        $users = $rankRepository->getOneToMany(
            $userRepository,
            $this->getId(),
            'rank',
            $isCached
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
     * @return Rank
     */
    public function setUsers(Collection $users): Rank
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

        /** @var RankRepository $rankRepository */
        $rankRepository = repository(RankRepository::class);

        /** @var Role $role */
        $role = $rankRepository->getManyToMany(
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
     * @return Rank
     */
    public function setRole(Role $role): Rank
    {
        return $this->setData('role', $role);
    }
}
