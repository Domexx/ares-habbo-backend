<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\Contract\UserCurrencyInterface;
use Ares\User\Repository\UserCurrencyRepository;
use Ares\User\Repository\UserRepository;

/**
 * Class UserCurrency
 *
 * @package Ares\User\Entity
 */
class UserCurrency extends DataObject implements UserCurrencyInterface
{
    /** @var string */
    public const TABLE = 'users_currency';

    /** @var array */
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /** @var string */
    public const PRIMARY_KEY = 'user_id';

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(UserCurrencyInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $user_id
     *
     * @return UserCurrency
     */
    public function setUserId(int $user_id): UserCurrency
    {
        return $this->setData(UserCurrencyInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->getData(UserCurrencyInterface::COLUMN_TYPE);
    }

    /**
     * @param int $type
     *
     * @return UserCurrency
     */
    public function setType(int $type): UserCurrency
    {
        return $this->setData(UserCurrencyInterface::COLUMN_TYPE, $type);
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->getData(UserCurrencyInterface::COLUMN_AMOUNT);
    }

    /**
     * @param int $amount
     *
     * @return UserCurrency
     */
    public function setAmount(int $amount): UserCurrency
    {
        return $this->setData(UserCurrencyInterface::COLUMN_AMOUNT, $amount);
    }

    /**
     * @return User|null
     *
     * @throws DataObjectManagerException
     */
    public function getUser(): ?User
    {
        /** @var User $user */
        $user = $this->getData('user');

        if ($user) {
            return $user;
        }

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var UserCurrencyRepository $userCurrencyRepository */
        $userCurrencyRepository = repository(UserCurrencyRepository::class);

        /** @var User $user */
        $user = $userCurrencyRepository->getOneToOne(
            $userRepository,
            $this->getUserId(),
            'id'
        );

        if (!$user) {
            return null;
        }

        $this->setUser($user);

        return $user;
    }

    public function setUser(User $user): UserCurrency
    {
        return $this->setData('user', $user);
    }
}
