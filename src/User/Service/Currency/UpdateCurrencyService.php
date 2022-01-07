<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Service\Currency;

use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\User\Entity\Contract\UserCurrencyInterface;
use Ares\User\Exception\UserCurrencyException;
use Ares\User\Interfaces\Response\UserResponseCodeInterface;
use Ares\User\Repository\UserCurrencyRepository;
use Ares\User\Repository\UserRepository;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
use Exception;

/**
 * Class UpdateCurrencyService
 *
 * @package Ares\User\Service\Currency
 */
class UpdateCurrencyService
{
    /**
     * UpdateCurrencyService constructor.
     *
     * @param UserCurrencyRepository $userCurrencyRepository
     */
    public function __construct(
        private UserCurrencyRepository $userCurrencyRepository,
        private UserRepository  $userRepository
    ) {}

    /**
     * Updates currency by given data.
     *
     * @param int $userId
     * @param int $type
     * @param int $amount
     *
     * @return
     * @throws UserCurrencyException
     */
    public function execute(int $userId, int $type, int $amount) : void
    {
        /** @var User $user */
        $user = $this->userRepository->get($userId, 'id', false, false);

        if(!$user) {
            throw new UserException(
                __('No user found with that ID'),
                UserResponseCodeInterface::RESPONSE_USER_NOT_FOUND,
                HttpResponseCodeInterface::HTTP_RESPONSE_NOT_FOUND
            );
        }

        if($type === -1) {
            $this->updateCredits($user, $amount);
        } else {
            $this->updateCurrency($user, $amount, $type);
        }
    }

    /**
     * Updates User Credits Amount
     * 
     * @param User $user
     * @param int $amount
     * 
     * @return
     */
    private function updateCredits(User $user, int $amount) : void {
        $user->setCredits($amount);

        try {
            $this->userRepository->save($user);
        } catch (Exception) {
            throw new UserCurrencyException(
                __('Currency could not be updated.'),
                UserResponseCodeInterface::RESPONSE_CURRENCY_NOT_UPDATED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Updates User Currency Amount based on Type
     * 
     * @param User $user
     * @param int $amount
     * @param int $type
     * 
     * @return
     */
    private function updateCurrency(User $user, int $amount, int $type) : void {
        /** @var UserCurrency $currency */
        $currency = $this->userCurrencyRepository->getUserCurrency($user->getId(), $type);

        if (!$currency) {
            throw new UserCurrencyException(
                __('No Currencies were found'),
                UserResponseCodeInterface::RESPONSE_CURRENCY_NOT_FOUND,
                HttpResponseCodeInterface::HTTP_RESPONSE_NOT_FOUND
            );
        }

        try {
            $this->userCurrencyRepository
            ->getDataObjectManager()
            ->where([
                UserCurrencyInterface::COLUMN_USER_ID => $user->getId(),
                UserCurrencyInterface::COLUMN_TYPE => $type
            ])
            ->update([UserCurrencyInterface::COLUMN_AMOUNT => $amount]);
        } catch (Exception) {
            throw new UserCurrencyException(
                __('Currency could not be updated.'),
                UserResponseCodeInterface::RESPONSE_CURRENCY_NOT_UPDATED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }
    }
}