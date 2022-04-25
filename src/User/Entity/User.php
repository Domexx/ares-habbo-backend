<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Repository\RoleRankRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleRank;
use Ares\Permission\Repository\PermissionRepository;
use Ares\Permission\Entity\Permission;
use Ares\User\Entity\Contract\UserInterface;
use Ares\User\Repository\UserCurrencyRepository;
use Ares\User\Repository\UserRepository;
use Ares\Framework\Model\Query\Collection;

/**
 * Class User
 *
 * @package Ares\User\Entity
 */
class User extends DataObject implements UserInterface
{
    /** @var string */
    public const TABLE = 'users';

    /** @var array */
    public const HIDDEN = [
        UserInterface::COLUMN_PASSWORD,
        UserInterface::COLUMN_AUTH_TICKET,
        UserInterface::COLUMN_IP_CURRENT,
        UserInterface::COLUMN_IP_REGISTER
    ];

    /** @var array */
    public const RELATIONS = [
        'role' => 'getRole',
        'currencies' => 'getCurrencies',
        'permissions' => 'getPermissions',
        'hidden' => 'getHidden'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(UserInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return User
     */
    public function setId(int $id): User
    {
        return $this->setData(UserInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->getData(UserInterface::COLUMN_USERNAME);
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): User
    {
        return $this->setData(UserInterface::COLUMN_USERNAME, $username);
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->getData(UserInterface::COLUMN_PASSWORD);
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        return $this->setData(UserInterface::COLUMN_PASSWORD, $password);
    }

    /**
     * @return string
     */
    public function getMail(): string
    {
        return $this->getData(UserInterface::COLUMN_MAIL);
    }

    /**
     * @param string $mail
     *
     * @return User
     */
    public function setMail(string $mail): User
    {
        return $this->setData(UserInterface::COLUMN_MAIL, $mail);
    }

    /**
     * @return int
     */
    public function getAccountCreated(): int {
        return $this->getData(UserInterface::COLUMN_ACCOUNT_CREATED);
    }

    /**
     * @param int $accountCreated
     * 
     * @return User
     */
    public function setAccountCreated(int $accountCreated): User {
        return $this->setData(UserInterface::COLUMN_ACCOUNT_CREATED, $accountCreated);
    }

    /**
     * @return string
     */
    public function getLook(): string
    {
        return $this->getData(UserInterface::COLUMN_LOOK);
    }

    /**
     * @param string $look
     *
     * @return User
     */
    public function setLook(string $look): User
    {
        return $this->setData(UserInterface::COLUMN_LOOK, $look);
    }

    /**
     * @return string
     */
    public function getGender(): string
    {
        return $this->getData(UserInterface::COLUMN_GENDER);
    }

    /**
     * @param string $gender
     *
     * @return User
     */
    public function setGender(string $gender): User
    {
        return $this->setData(UserInterface::COLUMN_GENDER, $gender);
    }

    /**
     * @return string|null
     */
    public function getMotto(): ?string
    {
        return $this->getData(UserInterface::COLUMN_MOTTO);
    }

    /**
     * @param string|null $motto
     *
     * @return User
     */
    public function setMotto(?string $motto): User
    {
        return $this->setData(UserInterface::COLUMN_MOTTO, $motto);
    }

    /**
     * @return int
     */
    public function getCredits(): int
    {
        return $this->getData(UserInterface::COLUMN_CREDITS);
    }

    /**
     * @param int $credits
     *
     * @return User
     */
    public function setCredits(int $credits): User
    {
        return $this->setData(UserInterface::COLUMN_CREDITS, $credits);
    }

    /**
     * @return int|null
     */
    public function getRank(): ?int
    {
        return $this->getData(UserInterface::COLUMN_RANK);
    }

    /**
     * @param int|null $rank
     *
     * @return User
     */
    public function setRank(?int $rank): User
    {
        return $this->setData(UserInterface::COLUMN_RANK, $rank);
    }

    /**
     * @return string|null
     */
    public function getAuthTicket(): ?string
    {
        return $this->getData(UserInterface::COLUMN_AUTH_TICKET);
    }

    /**
     * @param string|null $authTicket
     *
     * @return User
     */
    public function setAuthTicket(?string $authTicket): User
    {
        return $this->setData(UserInterface::COLUMN_AUTH_TICKET, $authTicket);
    }

    /**
     * @return string
     */
    public function getIpRegister(): string
    {
        return $this->getData(UserInterface::COLUMN_IP_REGISTER);
    }

    /**
     * @param string $ipRegister
     *
     * @return User
     */
    public function setIpRegister(string $ipRegister): User
    {
        return $this->setData(UserInterface::COLUMN_IP_REGISTER, $ipRegister);
    }

    /**
     * @return string|null
     */
    public function getIpCurrent(): ?string
    {
        return $this->getData(UserInterface::COLUMN_IP_CURRENT);
    }

    /**
     * @param string|null $ipCurrent
     *
     * @return User
     */
    public function setIpCurrent(?string $ipCurrent): User
    {
        return $this->setData(UserInterface::COLUMN_IP_CURRENT, $ipCurrent);
    }

    /**
     * @return int
     */
    public function getHomeRoom(): int
    {
        return $this->getData(UserInterface::COLUMN_HOME_ROOM);
    }

    /**
     * @param int $online
     *
     * @return User
     */
    public function setHomeRoom(int $homeRoom): User
    {
        return $this->setData(UserInterface::COLUMN_HOME_ROOM, $homeRoom);
    }

    /**
     * @return int
     */
    public function getOnline(): int
    {
        return $this->getData(UserInterface::COLUMN_ONLINE);
    }

    /**
     * @param int $online
     *
     * @return User
     */
    public function setOnline(int $online): User
    {
        return $this->setData(UserInterface::COLUMN_ONLINE, $online);
    }

    /**
     * @return int
     */
    public function getLastLogin(): int
    {
        return $this->getData(UserInterface::COLUMN_LAST_LOGIN);
    }

    /**
     * @param int $lastLogin
     *
     * @return User
     */
    public function setLastLogin(int $lastLogin): User
    {
        return $this->setData(UserInterface::COLUMN_LAST_LOGIN, $lastLogin);
    }

    /**
     * @return int|null
     */
    public function getLastOnline(): ?int
    {
        return $this->getData(UserInterface::COLUMN_LAST_ONLINE);
    }

    /**
     * @param int|null $lastOnline
     *
     * @return User
     */
    public function setLastOnline(?int $lastOnline): User
    {
        return $this->setData(UserInterface::COLUMN_LAST_ONLINE, $lastOnline);
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(UserInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt(\DateTime $createdAt): User
    {
        return $this->setData(UserInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(UserInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt(\DateTime $updatedAt): User
    {
        return $this->setData(UserInterface::COLUMN_UPDATED_AT, $updatedAt);
    }

    /**
     * @return Role|null
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

        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = repository(PermissionRepository::class);

        /** @var RoleRepository $roleRepository */
        $roleRepository = repository(RoleRepository::class);

        /** @var Role $role */
        $role = $permissionRepository->getManyToMany(
            $roleRepository,
            $this->getRank(),
            'ares_roles_rank',
            'rank_id',
            'role_id'
        )->first();

        if (!$role) {
            return null;
        }

        $this->setRole($role);

        return $role;
    }

    /**
     * @param Role $roles
     *
     * @return User
     */
    public function setRole(Role $roles): User
    {
        return $this->setData('role', $roles);
    }

    /**
     * @return Collection|null
     * @throws DataObjectManagerException
     */
    public function getPermissions(): ?Collection
    {
        $permissions = $this->getData('permissions');

        if ($permissions) {
            return $permissions;
        }

        /** @var RoleRankRepository $roleRankRepository */
        $roleRankRepository = repository(RoleRankRepository::class);

        /** @var RolePermissionRepository $rolePermissionRepository */
        $rolePermissionRepository = repository(RolePermissionRepository::class);

        /** @var RoleRank $roleRank */
        $roleRank = $roleRankRepository->getRoleRankByRankId($this->getRank());

        if (!isset($this) || !$roleRank) {
            return null;
        }

        /** @var Collection $permissions */
        $permissions = $rolePermissionRepository->getRolePermissions($roleRank->getRankId());

        if (!$permissions) {
            return null;
        }
        
        $this->setPermissions($permissions);

        return $permissions;
    }

    /**
     * @param Collection $permissions
     *
     * @return User
     */
    public function setPermissions(Collection $permissions): User
    {
        return $this->setData('permissions', $permissions);
    }

    public function getHidden()
    {
        $hidden = [
            UserInterface::COLUMN_PASSWORD => $this->getPassword(),
            UserInterface::COLUMN_AUTH_TICKET => $this->getAuthTicket(),
            UserInterface::COLUMN_IP_CURRENT => $this->getIpCurrent(),
            UserInterface::COLUMN_IP_REGISTER => $this->getIpRegister()
        ];

        $this->setHidden($hidden);

        return $hidden;
    }
    
    public function setHidden(array $hidden): User
    {
        return $this->setData('hidden', $hidden);    
    }

    /**
     * @return Collection|null
     *
     * @throws DataObjectManagerException
     */
    public function getCurrencies(): ?Collection
    {
        $currencies = $this->getData('currencies');

        if ($currencies) {
            return $currencies;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var UserCurrencyRepository $userCurrencyRepository */
        $userCurrencyRepository = repository(UserCurrencyRepository::class);

        $currencies = $userRepository->getOneToMany(
            $userCurrencyRepository,
            $this->getId(),
            'user_id'
        );

        if (!$currencies->toArray()) {
            return null;
        }

        $this->setCurrencies($currencies);

        return $currencies;
    }

    /**
     * @param Collection $currencies
     *
     * @return User
     */
    public function setCurrencies(Collection $currencies): User
    {
        return $this->setData('currencies', $currencies);
    }
}
