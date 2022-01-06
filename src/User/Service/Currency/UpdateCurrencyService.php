<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Service\Currency;

use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\User\Exception\UserCurrencyException;
use Ares\User\Interfaces\Response\UserResponseCodeInterface;
use Ares\User\Repository\UserCurrencyRepository;
use Ares\User\Repository\UserRepository;
use Ares\User\Entity\User;
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
     * @return void
     * @throws UserCurrencyException
     */
    public function execute(int $userId, int $type, int $amount): void
    {
        if($type === -1) {
            /** @var User $user */
            $user = $this->userRepository->get($userId, 'id', false, false);

            if(!$user) {
                throw new UserCurrencyException(
                    __('No Currencies were found'),
                    UserResponseCodeInterface::RESPONSE_CURRENCY_NOT_FOUND,
                    HttpResponseCodeInterface::HTTP_RESPONSE_NOT_FOUND
                );
            }

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
        } else {
            $currencies = $this->userCurrencyRepository->getUserCurrency($userId, $type);

            if (!$currencies) {
                throw new UserCurrencyException(
                    __('No Currencies were found'),
                    UserResponseCodeInterface::RESPONSE_CURRENCY_NOT_FOUND,
                    HttpResponseCodeInterface::HTTP_RESPONSE_NOT_FOUND
                );
            }

            foreach ($currencies as $currency) {
                $currency->setAmount($amount);
                try {
                    //TODO Improve this line...
                    $this->userCurrencyRepository
                            ->getDataObjectManager()
                            ->where([
                                'user_id' => $userId,
                                'type' => $type
                            ])
                            ->update([
                                'amount' => $amount
                            ]);

                } catch (Exception) {
                    throw new UserCurrencyException(
                        __('Currency could not be updated.'),
                        UserResponseCodeInterface::RESPONSE_CURRENCY_NOT_UPDATED,
                        HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
                    );
                }
            }
        }
    }
}