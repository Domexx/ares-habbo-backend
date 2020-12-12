<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\Contract\UserSettingInterface;
use Ares\User\Repository\UserRepository;
use Ares\User\Repository\UserSettingRepository;

/**
 * Class UserSetting
 *
 * @package Ares\User\Entity
 */
class UserSetting extends DataObject implements UserSettingInterface
{
    /** @var string */
    public const TABLE = 'users_settings';

    /** @var array */
    public const RELATIONS = [
        'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return UserSetting
     */
    public function setId(int $id): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return UserSetting
     */
    public function setUserId(int $userId): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getAchievementScore(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_ACHIEVEMENT_SCORE);
    }

    /**
     * @param int $achievementScore
     *
     * @return UserSetting
     */
    public function setAchievementScore(int $achievementScore): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_ACHIEVEMENT_SCORE, $achievementScore);
    }

    /**
     * @return mixed
     */
    public function getCanChangeName(): mixed
    {
        return $this->getData(UserSettingInterface::COLUMN_CAN_CHANGE_NAME);
    }

    /**
     * @param mixed $canChangeName
     *
     * @return UserSetting
     */
    public function setCanChangeName(mixed $canChangeName): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_CAN_CHANGE_NAME, $canChangeName);
    }

    /**
     * @return mixed
     */
    public function getBlockFollowing(): mixed
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_FOLLOWING);
    }

    /**
     * @param mixed $blockFollowing
     *
     * @return UserSetting
     */
    public function setBlockFollowing(mixed $blockFollowing): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_FOLLOWING, $blockFollowing);
    }

    /**
     * @return mixed
     */
    public function getBlockFriendRequests(): mixed
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_FRIENDREQUESTS);
    }

    /**
     * @param mixed $blockFriendRequests
     *
     * @return UserSetting
     */
    public function setBlockFriendRequests(mixed $blockFriendRequests): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_FRIENDREQUESTS, $blockFriendRequests);
    }

    /**
     * @return mixed
     */
    public function getBlockRoomInvites(): mixed
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_ROOMINVITES);
    }

    /**
     * @param mixed $blockRoomInvites
     *
     * @return UserSetting
     */
    public function setBlockRoomInvites(mixed $blockRoomInvites): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_ROOMINVITES, $blockRoomInvites);
    }

    /**
     * @return mixed
     */
    public function getBlockCameraFollow(): mixed
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_CAMERA_FOLLOW);
    }

    /**
     * @param mixed $blockCameraFollow
     *
     * @return UserSetting
     */
    public function setBlockCameraFollow(mixed $blockCameraFollow): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_CAMERA_FOLLOW, $blockCameraFollow);
    }

    /**
     * @return int
     */
    public function getOnlineTime(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_ONLINE_TIME);
    }

    /**
     * @param int $onlineTime
     *
     * @return UserSetting
     */
    public function setOnlineTime(int $onlineTime): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_ONLINE_TIME, $onlineTime);
    }

    /**
     * @return mixed
     */
    public function getBlockAlerts(): mixed
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_ALERTS);
    }

    /**
     * @param mixed $blockAlerts
     *
     * @return UserSetting
     */
    public function setBlockAlerts(mixed $blockAlerts): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_ALERTS, $blockAlerts);
    }

    /**
     * @return mixed
     */
    public function getIgnoreBots(): mixed
    {
        return $this->getData(UserSettingInterface::COLUMN_IGNORE_BOTS);
    }

    /**
     * @param mixed $ignoreBots
     *
     * @return UserSetting
     */
    public function setIgnoreBots(mixed $ignoreBots): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_IGNORE_BOTS, $ignoreBots);
    }

    /**
     * @return mixed
     */
    public function getIgnorePets(): mixed
    {
        return $this->getData(UserSettingInterface::COLUMN_IGNORE_PETS);
    }

    /**
     * @param mixed $ignorePets
     *
     * @return UserSetting
     */
    public function setIgnorePets(mixed $ignorePets): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_IGNORE_PETS, $ignorePets);
    }

    /**
     * @return User|null
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

        /** @var UserSettingRepository $userSettingRepository */
        $userSettingRepository = repository(UserSettingRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        $user = $userSettingRepository->getOneToOne(
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

    public function setUser(User $user): UserSetting
    {
        return $this->setData('user', $user);
    }
}
