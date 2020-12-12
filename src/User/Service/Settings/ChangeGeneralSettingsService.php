<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Service\Settings;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Rcon\Service\ExecuteRconCommandService;
use Ares\User\Entity\User;
use Ares\User\Entity\UserSetting;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Repository\UserSettingRepository;
use Exception;

/**
 * Class ChangeGeneralSettingsService
 *
 * @package Ares\User\Service\Settings
 */
class ChangeGeneralSettingsService
{
    /**
     * ChangeGeneralSettingsService constructor.
     *
     * @param UserSettingRepository     $userSettingRepository
     * @param ExecuteRconCommandService $executeRconCommandService
     */
    public function __construct(
        private UserSettingRepository $userSettingRepository,
        private ExecuteRconCommandService $executeRconCommandService
    ) {}

    /**
     * Changes user general settings by given user.
     *
     * @param User  $user
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws NoSuchEntityException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        /** @var UserSetting $userSetting */
        $userSetting = $this->userSettingRepository->get($user->getId(), 'user_id');

        /** @var UserSetting $userSetting */
        $userSetting = $this->userSettingRepository->save($this->getUpdatedUserSettings($userSetting, $data));

        try {
            $this->executeRconCommandService->execute(
                $user->getId(),
                [
                    'command' => 'updateuser',
                    'params' => [
                        'user_id' => $user->getId(),
                        'block_following' => $userSetting->getBlockFollowing(),
                        'block_friendrequests' => $userSetting->getBlockFriendRequests(),
                        'block_roominvites' => $userSetting->getBlockRoomInvites(),
                        'block_camera_follow' => $userSetting->getBlockCameraFollow()
                    ]
                ],
                true
            );
        } catch (Exception $exception) {
            throw new UserSettingsException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }

        return response()
            ->setData($userSetting);
    }

    /**
     * Returns updated user settings model.
     *
     * @param UserSetting $userSetting
     * @param array $data
     * @return UserSetting
     */
    private function getUpdatedUserSettings(UserSetting $userSetting, array $data): UserSetting
    {
        return $userSetting
            ->setBlockFollowing($data['block_following'])
            ->setBlockFriendrequests($data['block_friendrequests'])
            ->setBlockRoominvites($data['block_roominvites'])
            ->setBlockCameraFollow($data['block_camera_follow'])
            ->setBlockAlerts($data['block_alerts'])
            ->setIgnoreBots($data['ignore_bots'])
            ->setIgnorePets($data['ignore_pets']);
    }
}
