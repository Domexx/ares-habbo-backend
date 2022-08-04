<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Service\Register;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Repository\UserRepository;

/**
 * Class WeekRegisterCountService
 *
 * @package  Ares\User\Service\Register
 */
class WeekRegisterCountService
{
    /**
     * WeekRegisterCountService constructor.
     *
     */
    public function __construct(
        private UserRepository $userRepository
    ) {}

    /**
     * @return CustomResponseInterface
     *
     * @throws DataObjectManagerException|NoSuchEntityException
     */
    public function execute() : CustomResponseInterface
    {
        $week = $this->userRepository->getWeekRegistersCount();

        return response()->setData($week);
    }
}