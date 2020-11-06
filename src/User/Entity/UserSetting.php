<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
     * @param int $user_id
     *
     * @return UserSetting
     */
    public function setUserId(int $user_id): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return int
     */
    public function getAchievementScore(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_ACHIEVEMENT_SCORE);
    }

    /**
     * @param int $achievement_score
     *
     * @return UserSetting
     */
    public function setAchievementScore(int $achievement_score): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_ACHIEVEMENT_SCORE, $achievement_score);
    }

    /**
     * @return int
     */
    public function getCanChangeName(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_CAN_CHANGE_NAME);
    }

    /**
     * @param int $can_change_name
     *
     * @return UserSetting
     */
    public function setCanChangeName(int $can_change_name): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_CAN_CHANGE_NAME, $can_change_name);
    }

    /**
     * @return int
     */
    public function getBlockFollowing(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_FOLLOWING);
    }

    /**
     * @param int $block_following
     *
     * @return UserSetting
     */
    public function setBlockFollowing(int $block_following): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_FOLLOWING, $block_following);
    }

    /**
     * @return int
     */
    public function getBlockFriendRequests(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_FRIENDREQUESTS);
    }

    /**
     * @param int $block_friend_requests
     *
     * @return UserSetting
     */
    public function setBlockFriendRequests(int $block_friend_requests): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_FRIENDREQUESTS, $block_friend_requests);
    }

    /**
     * @return int
     */
    public function getBlockRoomInvites(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_ROOMINVITES);
    }

    /**
     * @param int $block_room_invites
     *
     * @return UserSetting
     */
    public function setBlockRoomInvites(int $block_room_invites): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_ROOMINVITES, $block_room_invites);
    }

    /**
     * @return int
     */
    public function getBlockCameraFollow(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_BLOCK_CAMERA_FOLLOW);
    }

    /**
     * @param int $block_camera_follow
     *
     * @return UserSetting
     */
    public function setBlockCameraFollow(int $block_camera_follow): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_BLOCK_CAMERA_FOLLOW, $block_camera_follow);
    }

    /**
     * @return int
     */
    public function getOnlineTime(): int
    {
        return $this->getData(UserSettingInterface::COLUMN_ONLINE_TIME);
    }

    /**
     * @param int $online_time
     *
     * @return UserSetting
     */
    public function setOnlineTime(int $online_time): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_ONLINE_TIME, $online_time);
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
     * @param int $ignore_pets
     *
     * @return UserSetting
     */
    public function setIgnorePets(int $ignore_pets): UserSetting
    {
        return $this->setData(UserSettingInterface::COLUMN_IGNORE_PETS, $ignore_pets);
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
