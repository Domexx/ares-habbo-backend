<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\UserOfTheHotel;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;
use Ares\User\Entity\UserOfTheHotel;
use Ares\User\Entity\UserSetting;
use Ares\User\Repository\UserOfTheHotelRepository;
use Ares\User\Repository\UserRepository;
use Ares\User\Repository\UserSettingRepository;

/**
 * Class ChangeUserOfTheHotelService
 *
 * @package Ares\User\Service\UserOfTheHotel
 */
class ChangeUserOfTheHotelService
{
    /**
     * @var UserSettingRepository
     */
    private UserSettingRepository $userSettingRepository;

    /**
     * @var UserOfTheHotelRepository
     */
    private UserOfTheHotelRepository $userOfTheHotelRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * ChangeUserOfTheHotelService constructor.
     *
     * @param UserSettingRepository    $userSettingRepository
     * @param UserOfTheHotelRepository $userOfTheHotelRepository
     * @param UserRepository           $userRepository
     */
    public function __construct(
        UserSettingRepository $userSettingRepository,
        UserOfTheHotelRepository $userOfTheHotelRepository,
        UserRepository $userRepository
    ) {
        $this->userSettingRepository = $userSettingRepository;
        $this->userOfTheHotelRepository = $userOfTheHotelRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @return CustomResponseInterface
     *
     * @throws DataObjectManagerException
     */
    public function execute(): CustomResponseInterface
    {
        /** @var UserOfTheHotel $currentUserOfTheHotel */
        $currentUserOfTheHotel = $this->userOfTheHotelRepository->getCurrentUser();

        if (!$currentUserOfTheHotel || $currentUserOfTheHotel->getToTimestamp() <= time()) {
            $nextUserOfTheHotel = $this->getNewUserOfTheHotel();

            /** @var UserOfTheHotel $nextUserOfTheHotel */
            $nextUserOfTheHotel = $this->userOfTheHotelRepository->save($nextUserOfTheHotel);
            $nextUserOfTheHotel->getUser();

            return response()
                ->setData($nextUserOfTheHotel);
        }

        return response()
            ->setData($currentUserOfTheHotel);
    }

    /**
     * @return UserOfTheHotel
     *
     * @throws DataObjectManagerException
     */
    private function getNewUserOfTheHotel(): UserOfTheHotel
    {
        /** @var UserSetting $eligibleUser */
        $eligibleUser = $this->userSettingRepository->getUserWithMostRespects();

        /** @var User $userData */
        $userData = $this->userRepository->get($eligibleUser->getUserId());

        $newUser = new UserOfTheHotel();

        return $newUser
            ->setUserId($userData->getId())
            ->setToTimestamp(strtotime('+1 week'))
            ->setCreatedAt(new \DateTime());
    }
}
