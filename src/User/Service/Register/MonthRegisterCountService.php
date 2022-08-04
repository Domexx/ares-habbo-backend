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
 * Class MonthRegisterCountService
 *
 * @package  Ares\User\Service\Register
 */
class MonthRegisterCountService
{
    /**
     * MonthRegisterCountService constructor.
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
        $week = $this->userRepository->getMonthRegistersCount();

        return response()->setData($week);
    }
}