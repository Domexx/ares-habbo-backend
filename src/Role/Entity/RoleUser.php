<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Role\Entity\Contract\RoleUserInterface;
use Ares\Role\Repository\RoleUserRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use DateTime;

/**
 * Class RoleUser
 *
 * @package Ares\Role\Entity
 */
class RoleUser extends DataObject implements RoleUserInterface
{
    /** @var string */
    public const TABLE = 'ares_roles_user';

    /** @var array */
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(RoleUserInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return RoleUser
     */
    public function setId(int $id): RoleUser
    {
        return $this->setData(RoleUserInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->getData(RoleUserInterface::COLUMN_ROLE_ID);
    }

    /**
     * @param int $role_id
     *
     * @return RoleUser
     */
    public function setRoleId(int $roleId): RoleUser
    {
        return $this->setData(RoleUserInterface::COLUMN_ROLE_ID, $roleId);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(RoleUserInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return RoleUser
     */
    public function setUserId(int $userId): RoleUser
    {
        return $this->setData(RoleUserInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->getData(RoleUserInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param DateTime $createdAt
     *
     * @return RoleUser
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        return $this->setData(RoleUserInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return User|null
     *
     * @throws DataObjectManagerException
     */
    public function getUser(): ?User
    {
        $user = $this->getData('user');

        if ($user) {
            return $user;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var RoleUserRepository $roleUserRepository */
        $roleUserRepository = repository(RoleUserRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $roleUserRepository->getOneToOne(
            $userRepository,
            $this->getUserId(),
            'id'
        );

        if (!$user) {
            return null;
        }

        $this->setUser($user);

        return $user;
    }

    /**
     * @param User $user
     *
     * @return RoleUser
     */
    public function setUser(User $user): RoleUser
    {
        return $this->setData('user', $user);
    }
}
