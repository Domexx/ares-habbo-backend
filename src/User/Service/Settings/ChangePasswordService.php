<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Service\Settings;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\User\Entity\User;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Interfaces\Response\UserResponseCodeInterface;
use Ares\User\Repository\UserRepository;
use Ares\User\Service\Auth\HashService;

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
     * @param HashService $hashService
     */
    public function __construct(
        private UserRepository $userRepository,
        private HashService $hashService
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

        if (!password_verify($oldPassword, $currentPassword)) {
            throw new UserSettingsException(
                __('Given old password does not match the current password'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_OLD_NOT_EQUALS_NEW,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        if (password_verify($password, $currentPassword)) {
            throw new UserSettingsException(
                __('Given password should be a different password than the current'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_DIFFERENT_PASSWORD,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var string $passwordHashed */
        $passwordHashed = $this->hashService->hash($password);

        /** @var User $user */
        $user = $this->userRepository->save($user->setPassword($passwordHashed));

        return response()
            ->setData($user);
    }
}
