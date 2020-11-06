<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Settings;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Ares\User\Entity\UserSetting;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Repository\UserRepository;
use Ares\User\Repository\UserSettingRepository;

/**
 * Class ChangeUsernameService
 *
 * @package Ares\User\Service\Settings
 */
class ChangeUsernameService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var UserSettingRepository
     */
    private UserSettingRepository $userSettingRepository;

    /**
     * ChangeUsernameService constructor.
     *
     * @param UserRepository $userRepository
     * @param UserSettingRepository $userSettingRepository
     */
    public function __construct(
        UserRepository $userRepository,
        UserSettingRepository $userSettingRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userSettingRepository = $userSettingRepository;
    }

    /**
     * Changes user name by given data.
     *
     * @param User   $user
     * @param string $username
     * @param string $password
     *
     * @return CustomResponseInterface
     * @throws UserSettingsException
     * @throws DataObjectManagerException
     */
    public function execute(User $user, string $username, string $password): CustomResponseInterface
    {
        /** @var UserSetting $userSetting */
        $userSetting = $this->userSettingRepository->get($user->getId(), 'user_id');

        if (!password_verify($password, $user->getPassword())) {
            throw new UserSettingsException(__('Given old password does not match the current password.'));
        }

        if (!$userSetting) {
            throw new UserSettingsException(__('Settings for given user does not exist.'));
        }

        if (!$userSetting->getCanChangeName()) {
            throw new UserSettingsException(__('User is not allowed to change username.'));
        }

        /** @var User $usernameExists */
        $usernameExists = $this->userRepository->get($username, 'username');

        if ($usernameExists) {
            throw new UserSettingsException(__('User with given username already exists.'));
        }

        /** @var User $user */
        $user = $this->userRepository->save($user->setUsername($username));

        return response()
            ->setData($user);
    }
}
