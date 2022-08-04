<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Room\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Room\Entity\Room;

/**
 * Class RoomRepository
 *
 * @package Ares\Room\Repository
 */
class RoomRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROOM_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROOM_COLLECTION_';

    /** @var string */
    protected string $entity = Room::class;

    /**
     * Searches rooms by search term.
     *
     * @param string $term
     * @param int    $page
     * @param int    $resultPerPage
     *
     * @return Room|null
     * @throws DataObjectManagerException|NoSuchEntityException
     */
    public function getRoomById(int $roomId): ?Room
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('id', $roomId)
            ->addRelation('guild')
            ->addRelation('user');

        return $this->getOneBy($searchCriteria);
    }

    /**
     * Searches rooms by search term.
     *
     * @param string $term
     * @param int    $page
     * @param int    $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function searchRooms(string $term, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('name', 'LIKE', '%'.$term.'%')
            ->orderBy('users', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedRoomList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('guild')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $ownerId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getUserRoomsPaginatedList(int $ownerId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('owner_id', $ownerId);

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $count
     * @return Collection|null
     * @throws NoSuchEntityException
    */
    public function getMostVisitedRooms(int $count = 1): ?Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('users', 'DESC')
            ->limit($count);

        return $this->getList($searchCriteria);
    }
}
