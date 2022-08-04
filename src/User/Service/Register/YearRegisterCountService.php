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
 * Class YearRegisterCountService
 *
 * @package  Ares\User\Service\Register
 */
class YearRegisterCountService
{
    /**
     * YearRegisterCountService constructor.
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
        $week = $this->userRepository->getYearRegistersCount();

        return response()->setData($week);
    }
}