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
use Ares\User\Entity\Contract\UserInterface;
use Ares\User\Entity\User;
use Ares\User\Exception\UserSettingsException;
use Ares\User\Interfaces\Response\UserResponseCodeInterface;
use Ares\User\Repository\UserRepository;

/**
 * Class ChangeRankService
 *
 * @package Ares\User\Service\Settings
 */
class ChangeRankService
{
    /**
     * ChangeRankService constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * Changes rank by given data.
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
    public function execute(User $user, array $parsedData): CustomResponseInterface
    {
        /** @var User $editUser */
        $editUser = $this->userRepository->get($parsedData[UserInterface::COLUMN_ID]);

        if($editUser->getId() == $user->getId()) {
            throw new UserSettingsException(
                __('You cannot use the same Username or a User with this username already exists'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_USER_USERNAME_EXISTS,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        if($editUser->getRank() >= $user->getRank()) {
            throw new UserSettingsException(
                __('You cannot use the same Username or a User with this username already exists'),
                UserResponseCodeInterface::RESPONSE_SETTINGS_USER_USERNAME_EXISTS,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $this->userRepository->save($editUser->setRank($parsedData[UserInterface::COLUMN_RANK]));

        return response()->setData($editUser);
    }
}
