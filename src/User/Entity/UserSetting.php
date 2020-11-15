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
     * @return int
     */
    public function getCanChangeName(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_CAN_CHANGE_NAME);
    }

    /**
     * @param int $canChangeName
     *
     * @return UserSetting
     */
    public function setCanChangeName(int $canChangeName): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_CAN_CHANGE_NAME, $canChangeName);
    }

    /**
     * @return int
     */
    public function getBlockFollowing(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_FOLLOWING);
    }

    /**
     * @param int $blockFollowing
     *
     * @return UserSetting
     */
    public function setBlockFollowing(int $blockFollowing): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_FOLLOWING, $blockFollowing);
    }

    /**
     * @return int
     */
    public function getBlockFriendRequests(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_FRIENDREQUESTS);
    }

    /**
     * @param int $blockFriendRequests
     *
     * @return UserSetting
     */
    public function setBlockFriendRequests(int $blockFriendRequests): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_FRIENDREQUESTS, $blockFriendRequests);
    }

    /**
     * @return int
     */
    public function getBlockRoomInvites(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_ROOMINVITES);
    }

    /**
     * @param int $blockRoomInvites
     *
     * @return UserSetting
     */
    public function setBlockRoomInvites(int $blockRoomInvites): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_ROOMINVITES, $blockRoomInvites);
    }

    /**
     * @return int
     */
    public function getBlockCameraFollow(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_CAMERA_FOLLOW);
    }

    /**
     * @param int $blockCameraFollow
     *
     * @return UserSetting
     */
    public function setBlockCameraFollow(int $blockCameraFollow): UserSetting
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
     * @return int
     */
    public function getBlockAlerts(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_ALERTS);
    }

    /**
     * @param int $block_alerts
     *
     * @return UserSetting
     */
    public function setBlockAlerts(int $block_alerts): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_ALERTS, $block_alerts);
    }

    /**
     * @return int
     */
    public function getIgnoreBots(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_IGNORE_BOTS);
    }

    /**
     * @param int $ignore_bots
     *
     * @return UserSetting
     */
    public function setIgnoreBots(int $ignore_bots): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_IGNORE_BOTS, $ignore_bots);
    }

    /**
     * @return int
     */
    public function getIgnorePets(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_IGNORE_PETS);
    }

    /**
     * @param int $ignorePets
     *
     * @return UserSetting
     */
    public function setIgnorePets(int $ignorePets): UserSetting
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
