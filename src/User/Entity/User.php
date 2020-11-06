<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Permission\Entity\Permission;
use Ares\Role\Repository\RoleRepository;
use Ares\User\Entity\Contract\UserInterface;
use Ares\User\Repository\UserCurrencyRepository;
use Ares\User\Repository\UserRepository;
use DateTime;
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
        'currencies' => 'getCurrencies'
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
     * @return Permission|null
     */
    public function getRankData(): ?Permission
    {
        return $this->getData(UserInterface::COLUMN_RANK);
    }

    /**
     * @param Permission|null $rank_data
     *
     * @return User
     */
    public function setRankData(?Permission $rank_data): User
    {
        return $this->setData(UserInterface::COLUMN_RANK, $rank_data);
    }

    /**
     * @return string|null
     */
    public function getAuthTicket(): ?string
    {
        return $this->getData(UserInterface::COLUMN_AUTH_TICKET);
    }

    /**
     * @param string|null $auth_ticket
     *
     * @return User
     */
    public function setAuthTicket(?string $auth_ticket): User
    {
        return $this->setData(UserInterface::COLUMN_AUTH_TICKET, $auth_ticket);
    }

    /**
     * @return string
     */
    public function getIpRegister(): string
    {
        return $this->getData(UserInterface::COLUMN_IP_REGISTER);
    }

    /**
     * @param string $ip_register
     *
     * @return User
     */
    public function setIpRegister(string $ip_register): User
    {
        return $this->setData(UserInterface::COLUMN_IP_REGISTER, $ip_register);
    }

    /**
     * @return string|null
     */
    public function getIpCurrent(): ?string
    {
        return $this->getData(UserInterface::COLUMN_IP_CURRENT);
    }

    /**
     * @param string|null $ip_current
     *
     * @return User
     */
    public function setIpCurrent(?string $ip_current): User
    {
        return $this->setData(UserInterface::COLUMN_IP_CURRENT, $ip_current);
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
     * @param int $last_login
     *
     * @return User
     */
    public function setLastLogin(int $last_login): User
    {
        return $this->setData(UserInterface::COLUMN_LAST_LOGIN, $last_login);
    }

    /**
     * @return int|null
     */
    public function getLastOnline(): ?int
    {
        return $this->getData(UserInterface::COLUMN_LAST_ONLINE);
    }

    /**
     * @param int|null $last_online
     *
     * @return User
     */
    public function setLastOnline(?int $last_online): User
    {
        return $this->setData(UserInterface::COLUMN_LAST_ONLINE, $last_online);
    }


    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->getData(UserInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param DateTime $created_at
     * @return User
     */
    public function setCreatedAt(DateTime $created_at): User
    {
        return $this->setData(UserInterface::COLUMN_CREATED_AT, $created_at);
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->getData(UserInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param DateTime $updated_at
     *
     * @return User
     */
    public function setUpdatedAt(DateTime $updated_at): User
    {
        return $this->setData(UserInterface::COLUMN_UPDATED_AT, $updated_at);
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
     *
     * @throws DataObjectManagerException
     */
    public function getCurrencies(): ?Collection
    {
        $currencies = $this->getData('currencies');

        if ($currencies) {
            return $currencies;
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
