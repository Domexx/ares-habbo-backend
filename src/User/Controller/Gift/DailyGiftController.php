<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller\Gift;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
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
     * @var PickGiftService
     */
    private PickGiftService $pickGiftService;

    /**
     * DailyGiftController constructor.
     *
     * @param PickGiftService $pickGiftService
     */
    public function __construct(
        PickGiftService $pickGiftService
    ) {
        $this->pickGiftService = $pickGiftService;
    }

    /**
     * Pick daily gift route.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DailyGiftException
     * @throws DataObjectManagerException
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
