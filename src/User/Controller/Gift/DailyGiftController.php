<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Controller\Gift;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\User\Entity\User;
use Ares\User\Exception\Gift\DailyGiftException;
use Ares\User\Service\Gift\PickGiftService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class DailyGiftController
 *
 * @package Ares\User\Controller\Gift
 */
class DailyGiftController extends BaseController
{
    /**
     * DailyGiftController constructor.
     *
     * @param PickGiftService $pickGiftService
     */
    public function __construct(
        private PickGiftService $pickGiftService
    ) {}

    /**
     * Pick daily gift route.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DailyGiftException
     * @throws NoSuchEntityException
     */
    public function pick(Request $request, Response $response): ResponseInterface
    {
        /** @var User $user */
        $user = user($request);

        $customResponse = $this->pickGiftService->execute($user);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
