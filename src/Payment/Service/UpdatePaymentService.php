<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Payment\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Payment\Entity\Contract\PaymentInterface;
use Ares\Payment\Entity\Payment;
use Ares\Payment\Repository\PaymentRepository;

/**
 * Class UpdatePaymentService
 *
 * @package Ares\Payment\Service
 */
class UpdatePaymentService
{
    /**
     * UpdatePaymentService constructor.
     *
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(
        private PaymentRepository $paymentRepository,
    ) {}

    /**
     * 
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException|ArticleException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var string $orderId */
        $orderId = $data['order_id'];

        /** @var Payment $payment */
        $payment = $this->paymentRepository->get($orderId, PaymentInterface::COLUMN_ORDER_ID);

        $payment = $this->updatePayment($payment, $data);

        /** @var Payment $payment */
        $payment = $this->paymentRepository->save($payment);

        return response()
            ->setData($payment);
    }

    /**
     * @param Payment $payment
     * @param array   $data
     *
     * @return Payment
     */
    private function updatePayment(Payment $payment, array $data): Payment
    {
        return $payment
                ->setStatus($data['status'])
                ->setDelivered($data['delivered'])
                ->setUpdatedAt(new \DateTime());
    }
}
