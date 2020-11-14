<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Settings;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Service\ExecuteRconCommandService;
use Ares\Role\Exception\RoleException;
use Ares\User\Entity\User;
use Ares\User\Entity\UserSetting;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Repository\UserSettingRepository;
use JsonException;

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
     * @throws RconException
     * @throws RoleException
     * @throws JsonException
     */
    public function execute(User $user, array $data): CustomResponseInterface
    {
        /** @var UserSetting $userSetting */
        $userSetting = $this->userSettingRepository->get($user->getId(), 'user_id');

        if (!$userSetting) {
            throw new UserSettingsException(__('Settings for given user does not exist.'));
        }

        /** @var UserSetting $userSetting */
        $userSetting = $this->userSettingRepository->save($this->getUpdatedUserSettings($userSetting, $data));

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
