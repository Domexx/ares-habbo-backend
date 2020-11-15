<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\User\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\Contract\UserOfTheHotelInterface;
use Ares\User\Repository\UserOfTheHotelRepository;
use Ares\User\Repository\UserRepository;

/**
 * Class UserOfTheHotel
 *
 * @package Ares\User\Entity
 */
class UserOfTheHotel extends DataObject implements UserOfTheHotelInterface
{
    /** @var string */
    public const TABLE = 'ares_uoth';

    /** @var array */
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(UserOfTheHotelInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return UserOfTheHotel
     */
    public function setId(int $id): UserOfTheHotel
    {
        return $this->setData(UserOfTheHotelInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(UserOfTheHotelInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return UserOfTheHotel
     */
    public function setUserId(int $userId): UserOfTheHotel
    {
        return $this->setData(UserOfTheHotelInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getToTimestamp(): int
    {
        return $this->getData(UserOfTheHotelInterface::COLUMN_TO_TIMESTAMP);
    }

    /**
     * @param int $toTimestamp
     *
     * @return UserOfTheHotel
     */
    public function setToTimestamp(int $toTimestamp): UserOfTheHotel
    {
        return $this->setData(UserOfTheHotelInterface::COLUMN_TO_TIMESTAMP, $toTimestamp);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(UserOfTheHotelInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return UserOfTheHotel
     */
    public function setCreatedAt(\DateTime $createdAt): UserOfTheHotel
    {
        return $this->setData(UserOfTheHotelInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return User|null
     *
     * @throws DataObjectManagerException
     */
    public function getUser(): ?User
    {
        /** @var User $user */
        $user = $this->getData('user');

        if ($user) {
            return $user;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var UserOfTheHotelRepository $userOfTheWeekRepository */
        $userOfTheWeekRepository = repository(UserOfTheHotelRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $userOfTheWeekRepository->getOneToOne(
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
     * @return UserOfTheHotel
     */
    public function setUser(User $user): UserOfTheHotel
    {
        return $this->setData('user', $user);
    }
}
