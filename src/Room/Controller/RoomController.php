<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Room\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Room\Entity\Room;
use Ares\Room\Repository\RoomRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class RoomController
 *
 * @package Ares\Room\Controller
 */
class RoomController extends BaseController
{
    /**
     * RoomController constructor.
     *
     * @param RoomRepository $roomRepository
     */
    public function __construct(
        private RoomRepository $roomRepository
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function room(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Room $room */
        $room = $this->roomRepository->get($id);
        $room->getGuild();
        $room->getUser();

        return $this->respond(
            $response,
            response()
                ->setData($room)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
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

        $rooms = $this->roomRepository
            ->getPaginatedRoomList(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($rooms)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws NoSuchEntityException
     */
    public function mostVisited(Request $request, Response $response): Response
    {
        /** @var Room $room */
        $room = $this->roomRepository->getMostVisitedRoom();

        return $this->respond(
            $response,
            response()
                ->setData($room)
        );
    }
}
