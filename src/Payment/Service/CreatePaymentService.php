<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Payment\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Payment\Entity\Payment;
use Ares\Payment\Exception\PaymentException;
use Ares\Payment\Interfaces\Response\PaymentResponseCodeInterface;
use Ares\Payment\Repository\PaymentRepository;

/**
 * Class CreatePaymentService
 *
 * @package Ares\Payment\Service
 */
class CreatePaymentService
{
    /**
     * CreatePaymentService constructor.
     *
     * @param PaymentRepository  $paymentRepository
     */
    public function __construct(
        private PaymentRepository $paymentRepository
    ) {}

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws PaymentException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $payment = $this->getNewPayment($userId, $data);

        /** @var Payment $existingPayment */
        $existingPayment = $this->paymentRepository->getExistingPayment($payment->getUserId());

        if ($existingPayment) {
            throw new PaymentException(
                __('You already have an ongoing payment'),
                PaymentResponseCodeInterface::RESPONSE_PAYMENT_ALREADY_ONGOING,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var Payment $payment */
        $payment = $this->paymentRepository->save($payment);

        return response()
            ->setData($payment);
    }

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return Payment
     */
    public function getNewPayment(int $userId, array $data): Payment
    {
        $payment = new Payment();

        return $payment
            ->setCode($data['code'])
            ->setUserId($userId)
            ->setProcessed(0)
            ->setType(0);
    }
}
