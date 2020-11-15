<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\User\Service\Settings;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Repository\UserRepository;

/**
 * Class ChangePasswordService
 *
 * @package Ares\User\Service\Settings
 */
class ChangePasswordService
{
    /**
     * ChangePasswordService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Changes user password.
     *
     * @param User   $user
     * @param string $password
     * @param string $oldPassword
     *
     * @return CustomResponseInterface
     * @throws UserSettingsException
     * @throws DataObjectManagerException
     */
    public function execute(User $user, string $password, string $oldPassword): CustomResponseInterface
    {
        $currentPassword = $user->getPassword();
        $passwordHashed = password_hash(
            $password,
            PASSWORD_ARGON2ID,
            ['memory_cost' => 8, 'time_cost' => 1, 'threads' => 1]
        );

        if (!password_verify($oldPassword, $passwordHashed)) {
            throw new UserSettingsException(__('Given old password does not match the current password'));
        }

        if (password_verify($password, $currentPassword)) {
            throw new UserSettingsException(__('Given password should be a different password than the current'));
        }

        /** @var User $user */
        $user = $this->userRepository->save($user->setPassword($password));

        return response()
            ->setData($user);
    }
}
