<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
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
    public const TABLE = 'ares_shop_payments';

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
     * @param int $userId
     *
     * @return Payment
     */
    public function setUserId(int $userId): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return string
     */
    public function getOfferId(): string
    {
        return $this->getData(PaymentInterface::COLUMN_OFFER_ID);
    }

    /**
     * @param string $offer
     *
     * @return Payment
     */
    public function setOfferId(string $offer): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_OFFER_ID, $offer);
    }

    /**
     * @return string
     */
    public function getOrderId(): string
    {
        return $this->getData(PaymentInterface::COLUMN_ORDER_ID);
    }

    /**
     * @param string $orderId
     *
     * @return Payment
     */
    public function setOrderId(string $orderId): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_ORDER_ID, $orderId);
    }

    /**
     * @return string
     */
    public function getPayerId(): string
    {
        return $this->getData(PaymentInterface::COLUMN_PAYER_ID);
    }

    /**
     * @param string $payerId
     *
     * @return Payment
     */
    public function setPayerId(string $payerId): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_PAYER_ID, $payerId);
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getData(PaymentInterface::COLUMN_STATUS);
    }

    /**
     * @param string $status
     *
     * @return Payment
     */
    public function setStatus(string $status): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_STATUS, $status);
    }

    /**
     * @return string
     */
    public function getDelivered(): string
    {
        return $this->getData(PaymentInterface::COLUMN_DELIVERED);
    }

    /**
     * @param string $delivered
     *
     * @return Payment
     */
    public function setDelivered(string $delivered): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_DELIVERED, $delivered);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(PaymentInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Payment
     */
    public function setCreatedAt(\DateTime $createdAt): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(PaymentInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Payment
     */
    public function setUpdatedAt(\DateTime $updatedAt): Payment
    {
        return $this->setData(PaymentInterface::COLUMN_UPDATED_AT, $updatedAt);
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

        if (!isset($this)) {
            return null;
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
