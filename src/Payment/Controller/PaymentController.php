<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Payment\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Payment\Entity\Contract\PaymentInterface;
use Ares\Payment\Entity\Payment;
use Ares\Payment\Exception\PaymentException;
use Ares\Payment\Repository\PaymentRepository;
use Ares\Payment\Service\CreatePaymentService;
use Ares\Payment\Service\DeletePaymentService;
use Ares\Payment\Service\UpdatePaymentService;
use Ares\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class PaymentController
 *
 * @package Ares\Payment\Controller
 */
class PaymentController extends BaseController
{
    /**
     * PaymentController constructor.
     *
     * @param PaymentRepository    $paymentRepository
     * @param CreatePaymentService $createPaymentService
     * @param UpdatePaymentService $updatePaymentService
     * @param ValidationService    $validationService
     * @param DeletePaymentService $deletePaymentService
     */
    public function __construct(
        private PaymentRepository $paymentRepository,
        private CreatePaymentService $createPaymentService,
        private UpdatePaymentService $updatePaymentService,
        private ValidationService $validationService,
        private DeletePaymentService $deletePaymentService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws PaymentException
     * @throws ValidationException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            PaymentInterface::COLUMN_OFFER_ID => 'required',
            PaymentInterface::COLUMN_USER_ID => 'required',
            PaymentInterface::COLUMN_ORDER_ID => 'required',
            PaymentInterface::COLUMN_PAYER_ID => 'required',
            PaymentInterface::COLUMN_STATUS => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->createPaymentService
            ->execute(
                $user->getId(),
                $parsedData
            );

        return $this->respond(
            $response,
            $customResponse
        );
    }

    
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws PaymentException
     * @throws ValidationException
     */
    public function update(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            PaymentInterface::COLUMN_ORDER_ID => 'required',
            PaymentInterface::COLUMN_STATUS => 'required',
            PaymentInterface::COLUMN_DELIVERED => 'required'
        ]);

        $customResponse = $this->updatePaymentService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws NoSuchEntityException
     */
    public function payment(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Payment $payment */
        $payment = $this->paymentRepository->get($id);

        return $this->respond(
            $response,
            response()
                ->setData($payment)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $payments = $this->paymentRepository
            ->getPaginatedPayments(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($payments)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws PaymentException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deletePaymentService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
