<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Payment\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Payment\Entity\Contract\PaymentInterface;
use Ares\Payment\Repository\PaymentRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Payment
 *
 * @package Ares\Payment\Entity
 */
class Payment extends DataObject implements PaymentInterface
{
    /** @var string */
    public const TABLE = 'ares_payments';

    /** @var array **/
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(PaymentInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Payment
     */
    public function setId(int $id): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(PaymentInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $user_id
     *
     * @return Payment
     */
    public function setUserId(int $user_id): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->getData(PaymentInterface::COLUMN_CODE);
    }

    /**
     * @param string $code
     *
     * @return Payment
     */
    public function setCode(string $code): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_CODE, $code);
    }

    /**
     * @return int
     */
    public function getProcessed(): int
    {
        return $this->getData(PaymentInterface::COLUMN_PROCESSED);
    }

    /**
     * @param int $processed
     *
     * @return Payment
     */
    public function setProcessed(int $processed): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_PROCESSED, $processed);
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->getData(PaymentInterface::COLUMN_TYPE);
    }

    /**
     * @param int $type
     *
     * @return Payment
     */
    public function setType(int $type): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_TYPE, $type);
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

        /** @var PaymentRepository $paymentRepository **/
        $paymentRepository = repository(PaymentRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $paymentRepository->getOneToOne(
            $userRepository,
            $this->getUserId(),
            'id'
        );

        if (!$user) {
            return null;
        }

        $this->setData('user', $user);


        return $user;
    }

    /**
     * @param User $user
     *
     * @return Payment
     */
    public function setUser(User $user): Payment
    {
        return $this->setData('user', $user);
    }
}
