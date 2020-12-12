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
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Service\ValidationService;
use Ares\Payment\Entity\Contract\PaymentInterface;
use Ares\Payment\Entity\Payment;
use Ares\Payment\Exception\PaymentException;
use Ares\Payment\Interfaces\Response\PaymentResponseCodeInterface;
use Ares\Payment\Repository\PaymentRepository;
use Ares\Payment\Service\CreatePaymentService;
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
     * @param   PaymentRepository       $paymentRepository
     * @param   CreatePaymentService    $createPaymentService
     * @param   ValidationService       $validationService
     */
    public function __construct(
        private PaymentRepository $paymentRepository,
        private CreatePaymentService $createPaymentService,
        private ValidationService $validationService
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
            PaymentInterface::COLUMN_CODE => 'required',
            PaymentInterface::COLUMN_TYPE => 'required|numeric'
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

        $deleted = $this->paymentRepository->delete($id);

        if (!$deleted) {
            throw new PaymentException(
                __('Payment could not be deleted'),
                PaymentResponseCodeInterface::RESPONSE_PAYMENT_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
