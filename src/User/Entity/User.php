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
use Ares\Role\Repository\RoleUserRepository;
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
        UserInterface::COLUMN_MAIL,
        UserInterface::COLUMN_AUTH_TICKET,
        UserInterface::COLUMN_IP_CURRENT,
        UserInterface::COLUMN_IP_REGISTER
    ];

    /** @var array */
    public const RELATIONS = [
        'roles' => 'getRoles',
        'currencies' => 'getCurrencies',
        'permissions' => 'getPermissions'
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
     * @return Collection|null
     * @throws DataObjectManagerException
     */
    public function getRoles(): ?Collection
    {
        $roles = $this->getData('roles');

        if ($roles) {
            return $roles;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var RoleRepository $roleRepository */
        $roleRepository = repository(RoleRepository::class);

        $roles = $userRepository->getManyToMany(
            $roleRepository,
            $this->getId(),
            'ares_roles_user',
            'user_id',
            'role_id'
        );

        if (!$roles->toArray()) {
            return null;
        }

        $this->setRoles($roles);

        return $roles;
    }

    /**
     * @param Collection $roles
     *
     * @return User
     */
    public function setRoles(Collection $roles): User
    {
        return $this->setData('roles', $roles);
    }

    /**
     * @return Collection|null
     * @throws DataObjectManagerException
     */
    public function getPermissions(): ?array
    {
        $permissions = $this->getData('permissions');

        if ($permissions) {
            return $permissions;
        }

        /** @var RoleUserRepository $roleUserRepository */
        $roleUserRepository = repository(RoleUserRepository::class);

        /** @var RoleHierarchyRepository $roleHierarchyRepository */
        $roleHierarchyRepository = repository(RoleHierarchyRepository::class);

        /** @var array $userRoleIds */
        $userRoleIds = $roleUserRepository->getUserRoleIds($this->getId());

        if (!isset($this) || !$userRoleIds) {
            return null;
        }

        /** @var array $allRoleIds */
        $allRoleIds = $roleHierarchyRepository->getAllRoleIdsHierarchy($userRoleIds);

        /** @var array $permissions */
        $permissions = $roleUserRepository->getUserPermissions($allRoleIds);

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
    public function setPermissions(array $permissions): User
    {
        return $this->setData('permissions', $permissions);
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
