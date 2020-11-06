<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Service\Currency;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\UserCurrency;
use Ares\User\Repository\UserCurrencyRepository;

/**
 * Class CreateCurrencyService
 *
 * @package Ares\User\Service\Currency
 */
class CreateCurrencyService
{
    /**
     * @var UserCurrencyRepository
     */
    private UserCurrencyRepository $userCurrencyRepository;

    /**
     * CreateCurrencyService constructor.
     *
     * @param   UserCurrencyRepository  $userCurrencyRepository
     */
    public function __construct(
        UserCurrencyRepository $userCurrencyRepository
    ) {
        $this->userCurrencyRepository = $userCurrencyRepository;
    }

    /**
     * Creates new user currency by given data.
     *
     * @param int $user_id
     * @param int $type
     * @param int $amount
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     */
    public function execute(int $user_id, int $type, int $amount): CustomResponseInterface
    {
        /** @var UserCurrency $userCurrency */
        $userCurrency = $this->userCurrencyRepository
            ->save(
                $this->getNewUserCurrency(
                    $user_id,
                    $type,
                    $amount
                )
            );

        return response()
            ->setData($userCurrency);
    }

    /**
     * Returns new user currency object.
     *
     * @param int $user_id
     * @param int $type
     * @param int $amount
     *
     * @return UserCurrency
     */
    private function getNewUserCurrency(int $user_id, int $type, int $amount): UserCurrency
    {
        $userCurrency = new UserCurrency();

        return $userCurrency
            ->setUserId($user_id)
            ->setType($type)
            ->setAmount($amount);
    }
}
