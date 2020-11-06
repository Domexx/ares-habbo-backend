<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\User\Service\UserOfTheHotel\ChangeUserOfTheHotelService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class UserOfTheHotelController
 *
 * @package Ares\User\Controller
 */
class UserOfTheHotelController extends BaseController
{
    /**
     * @var ChangeUserOfTheHotelService
     */
    private ChangeUserOfTheHotelService $changeUserOfTheHotelService;

    /**
     * UserOfTheWeekController constructor.
     *
     * @param ChangeUserOfTheHotelService $changeUserOfTheHotelService
     */
    public function __construct(
        ChangeUserOfTheHotelService $changeUserOfTheHotelService
    ) {
        $this->changeUserOfTheHotelService = $changeUserOfTheHotelService;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function getUserOfTheHotel(Request $request, Response $response): Response
    {
        $userOfTheHotel = $this->changeUserOfTheHotelService->execute();

        return $this->respond(
            $response,
            $userOfTheHotel
        );
    }
}
