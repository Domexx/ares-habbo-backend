<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\Contract\UserCurrencyInterface;
use Ares\User\Exception\UserCurrencyException;
use Ares\User\Service\Currency\UpdateCurrencyService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserCurrencyController
 *
 * @package Ares\User\Controller
 */
class UserCurrencyController extends BaseController
{
    /**
     * UserCurrencyController constructor.
     *
     * @param   ValidationService      $validationService
     * @param   UpdateCurrencyService  $updateCurrencyService
     */
    public function __construct(
        private ValidationService $validationService,
        private UpdateCurrencyService $updateCurrencyService
    ) {}

    /**
     * Updates user currency by given data.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws UserCurrencyException
     * @throws ValidationException
     */
    public function update(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserCurrencyInterface::COLUMN_USER_ID => 'required',
            UserCurrencyInterface::COLUMN_TYPE => 'required',
            UserCurrencyInterface::COLUMN_AMOUNT => 'required'
        ]);

        $this->updateCurrencyService->execute(
            $parsedData['user_id'],
            $parsedData['type'],
            $parsedData['amount']
        );

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
