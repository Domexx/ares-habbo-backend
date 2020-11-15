<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Ban\Entity;

use Ares\Ban\Entity\Contract\BanInterface;
use Ares\Ban\Repository\BanRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Ban
 *
 * @package Ares\Ban\Entity
 */
class Ban extends DataObject implements BanInterface
{
    /** @var string */
    public const TABLE = 'bans';

    /*** @var array */
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(BanInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Ban
     */
    public function setId(int $id): Ban
    {
        return $this->setData(BanInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(BanInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Ban
     */
    public function setUserId(int $userId): Ban
    {
        return $this->setData(BanInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->getData(BanInterface::COLUMN_IP);
    }

    /**
     * @param string $ip
     *
     * @return Ban
     */
    public function setIp(string $ip): Ban
    {
        return $this->setData(BanInterface::COLUMN_IP, $ip);
    }

    /**
     * @return string
     */
    public function getMachineId(): string
    {
        return $this->getData(BanInterface::COLUMN_MACHINE_ID);
    }

    /**
     * @param string $machineId
     *
     * @return Ban
     */
    public function setMachineId(string $machineId): Ban
    {
        return $this->setData(BanInterface::COLUMN_MACHINE_ID, $machineId);
    }

    /**
     * @return int
     */
    public function getUserStaffId(): int
    {
        return $this->getData(BanInterface::COLUMN_USER_STAFF_ID);
    }

    /**
     * @param int $userStaffId
     *
     * @return Ban
     */
    public function setUserStaffId(int $userStaffId): Ban
    {
        return $this->setData(BanInterface::COLUMN_USER_STAFF_ID, $userStaffId);
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->getData(BanInterface::COLUMN_TIMESTAMP);
    }

    /**
     * @param int $timestamp
     *
     * @return Ban
     */
    public function setTimestamp(int $timestamp): Ban
    {
        return $this->setData(BanInterface::COLUMN_TIMESTAMP, $timestamp);
    }

    /**
     * @return int
     */
    public function getBanExpire(): int
    {
        return $this->getData(BanInterface::COLUMN_BAN_EXPIRE);
    }

    /**
     * @param int $banExpire
     *
     * @return Ban
     */
    public function setBanExpire(int $banExpire): Ban
    {
        return $this->setData(BanInterface::COLUMN_BAN_EXPIRE, $banExpire);
    }

    /**
     * @return string
     */
    public function getBanReason(): string
    {
        return $this->getData(BanInterface::COLUMN_BAN_REASON);
    }

    /**
     * @param string $banReason
     *
     * @return Ban
     */
    public function setBanReason(string $banReason): Ban
    {
        return $this->setData(BanInterface::COLUMN_BAN_REASON, $banReason);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->getData(BanInterface::COLUMN_TYPE);
    }

    /**
     * @param string $type
     *
     * @return Ban
     */
    public function setType(string $type): Ban
    {
        return $this->setData(BanInterface::COLUMN_TYPE, $type);
    }

    /**
     * @return int
     */
    public function getCfhTopic(): int
    {
        return $this->getData(BanInterface::COLUMN_CFH_TOPIC);
    }

    /**
     * @param int $cfhTopic
     *
     * @return Ban
     */
    public function setCfhTopic(int $cfhTopic): Ban
    {
        return $this->setData(BanInterface::COLUMN_CFH_TOPIC, $cfhTopic);
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

        /** @var BanRepository $banRepository */
        $banRepository = repository(BanRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $banRepository->getOneToOne(
            $userRepository,
            $this->getUserStaffId(),
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
     * @return Ban
     */
    public function setUser(User $user): Ban
    {
        return $this->setData('user', $user);
    }
}
