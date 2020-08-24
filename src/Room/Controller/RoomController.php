<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Model\Adapter\DoctrineSearchCriteria;
use Ares\Room\Entity\Room;
use Ares\Room\Exception\RoomException;
use Ares\Room\Repository\RoomRepository;
use Jhg\DoctrinePagination\Collection\PaginatedArrayCollection;
use Phpfastcache\Exceptions\PhpfastcacheSimpleCacheException;
use Psr\Cache\InvalidArgumentException;
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
     * @var RoomRepository
     */
    private RoomRepository $roomRepository;

    /**
     * @var DoctrineSearchCriteria
     */
    private DoctrineSearchCriteria $searchCriteria;

    /**
     * RoomController constructor.
     *
     * @param   RoomRepository          $roomRepository
     * @param   DoctrineSearchCriteria  $searchCriteria
     */
    public function __construct(
        RoomRepository $roomRepository,
        DoctrineSearchCriteria $searchCriteria
    ) {
        $this->roomRepository = $roomRepository;
        $this->searchCriteria = $searchCriteria;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param          $args
     *
     * @return Response
     * @throws RoomException
     * @throws PhpfastcacheSimpleCacheException
     * @throws InvalidArgumentException
     */
    public function room(Request $request, Response $response, $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Room $room */
        $room = $this->roomRepository->get((int)$id);

        if (is_null($room)) {
            throw new RoomException(__('No specific Room found'), 404);
        }

        return $this->respond(
            $response,
            response()->setData($room->toArray())
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param          $args
     *
     * @return Response
     * @throws InvalidArgumentException
     * @throws PhpfastcacheSimpleCacheException
     * @throws RoomException
     */
    public function list(Request $request, Response $response, $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $this->searchCriteria->setPage((int)$page)
            ->setLimit((int)$resultPerPage)
            ->addOrder('id', 'DESC');

        $rooms = $this->roomRepository->paginate($this->searchCriteria);

        if ($rooms->isEmpty()) {
            throw new RoomException(__('No Rooms were found'), 404);
        }

        /** @var PaginatedArrayCollection $list */
        $list = [];
        foreach ($rooms as $room) {
            $list[] = $room->toArray();
        }

        return $this->respond(
            $response,
            response()->setData($list)
        );
    }
}
