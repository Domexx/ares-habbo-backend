<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Payment\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Payment\Entity\Payment;
use Ares\Payment\Exception\PaymentException;
use Ares\Payment\Repository\PaymentRepository;

/**
 * Class CreatePaymentService
 *
 * @package Ares\Payment\Service
 */
class CreatePaymentService
{

    /**
     * @var PaymentRepository
     */
    private PaymentRepository $paymentRepository;

    /**
     * CreatePaymentService constructor.
     *
     * @param   PaymentRepository  $paymentRepository
     */
    public function __construct(
        PaymentRepository $paymentRepository
    ) {
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws PaymentException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $payment = $this->getNewPayment($userId, $data);

        /** @var Payment $existingPayment */
        $existingPayment = $this->paymentRepository->getExistingPayment($payment->getUserId());

        if ($existingPayment) {
            throw new PaymentException(__('You already have an ongoing payment, wait till its processed'));
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
