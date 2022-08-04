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
     *
     * @param          $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function getAllRooms(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $rooms = $this->roomRepository->getPaginatedRoomList($page, $resultPerPage);

        return $this->respond($response, response()->setData($rooms));
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
    */
    public function getRoomById(Request $request, Response $response, array $args): Response
    {
        /** @var int $roomId */
        $roomId = $args['id'];

        /** @var Room $room */
        $room = $this->roomRepository->getRoomById($roomId);

        return $this->respond($response, response()->setData($room));
    }

    /**
     * Searches with term in groups, rooms and news.
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function searchRooms(Request $request, Response $response, array $args): Response
    {
        /** @var string $term */
        $term = $args['term'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $rooms = $this->roomRepository->searchRooms($term, $page, $resultPerPage);

        return $this->respond($response, response()->setData($rooms));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws NoSuchEntityException
     */
    public function getMostVisitedTop(Request $request, Response $response, array $args): Response
    {
        /** @var int $top */
        $top = $args['top'];

        /** @var Room $room */
        $room = $this->roomRepository->getMostVisitedRooms($top);

        return $this->respond($response, response()->setData($room));
    }
}
