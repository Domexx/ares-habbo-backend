<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
     * @param int $user_id
     *
     * @return UserOfTheHotel
     */
    public function setUserId(int $user_id): UserOfTheHotel
    {
        return $this->setData(UserOfTheHotelInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return int
     */
    public function getToTimestamp(): int
    {
        return $this->getData(UserOfTheHotelInterface::COLUMN_TO_TIMESTAMP);
    }

    /**
     * @param int $to_timestamp
     *
     * @return UserOfTheHotel
     */
    public function setToTimestamp(int $to_timestamp): UserOfTheHotel
    {
        return $this->setData(UserOfTheHotelInterface::COLUMN_TO_TIMESTAMP, $to_timestamp);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(UserOfTheHotelInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $created_at
     *
     * @return UserOfTheHotel
     */
    public function setCreatedAt(\DateTime $created_at): UserOfTheHotel
    {
        return $this->setData(UserOfTheHotelInterface::COLUMN_CREATED_AT, $created_at);
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
