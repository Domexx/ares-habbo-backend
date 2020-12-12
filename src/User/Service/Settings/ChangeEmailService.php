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
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\User\Entity\User;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Interfaces\Response\UserResponseCodeInterface;
use Ares\User\Repository\UserRepository;

/**
 * Class ChangeEmailService
 *
 * @package Ares\User\Service\Settings
 */
class ChangeEmailService
{
    /**
     * ChangeEmailService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Changes email by given data.
     *
     * @param User   $user
     * @param string $email
     * @param string $password
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws UserSettingsException
     * @throws NoSuchEntityException
     */
    public function execute(User $user, string $email, string $password): CustomResponseInterface
    {
        $currentEmail = $user->getMail();

        if (!password_verify($password, $user->getPassword())) {
            throw new UserSettingsException(
                __('Given old password does not match the current password'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_OLD_NOT_EQUALS_NEW,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        if ($currentEmail === $email) {
            throw new UserSettingsException(
                __('Given E-Mail should be different to current E-Mail'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_DIFFERENT_EMAIL,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var User $emailExists */
        $emailExists = $this->userRepository->get($email, 'mail', true);

        if ($emailExists) {
            throw new UserSettingsException(
                __('User with given E-Mail already exists'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_USER_EMAIL_EXISTS,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $this->userRepository->save($user->setMail($email));

        return response()
            ->setData($user);
    }
}
