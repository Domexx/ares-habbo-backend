<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
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
     * UserOfTheWeekController constructor.
     *
     * @param ChangeUserOfTheHotelService $changeUserOfTheHotelService
     */
    public function __construct(
        private ChangeUserOfTheHotelService $changeUserOfTheHotelService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException|NoSuchEntityException
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
