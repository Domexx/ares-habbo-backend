<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Payment\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Payment\Entity\Payment;
use Ares\Payment\Exception\PaymentException;
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
     * @var PaymentRepository
     */
    private PaymentRepository $paymentRepository;

    /**
     * @var CreatePaymentService
     */
    private CreatePaymentService $createPaymentService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * PaymentController constructor.
     *
     * @param   PaymentRepository       $paymentRepository
     * @param   CreatePaymentService    $createPaymentService
     * @param   ValidationService       $validationService
     */
    public function __construct(
        PaymentRepository $paymentRepository,
        CreatePaymentService $createPaymentService,
        ValidationService $validationService
    ) {
        $this->paymentRepository = $paymentRepository;
        $this->createPaymentService = $createPaymentService;
        $this->validationService = $validationService;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws PaymentException
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'code' => 'required',
            'type' => 'required|numeric'
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
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws PaymentException
     */
    public function payment(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Payment $payment */
        $payment = $this->paymentRepository->get((int) $id);

        if (!$payment) {
            throw new PaymentException(__('No specific Payment found'), 404);
        }

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
                (int) $page,
                (int) $resultPerPage
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

        $deleted = $this->paymentRepository->delete((int) $id);

        if (!$deleted) {
            throw new PaymentException(__('Payment could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
