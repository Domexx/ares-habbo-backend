<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rank\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Rank\Entity\Rank;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Model\Query\PaginatedCollection;

/**
 * Class RankRepository
 *
 * @package Ares\Rank\Repository
 */
class RankRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_RANK_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_RANK_COLLECTION_';

    /** @var string */
    protected string $entity = Rank::class;

    /**
     * 
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
    */
    public function getAllRanks(int $page, int $resultPerPage) : PaginatedCollection {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('role');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     *
     *
     * @return Collection
     * @throws DataObjectManagerException
    */
    public function getRanksList() : Collection {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('role');

        return $this->getList($searchCriteria, false);
    }

    public function getRankById(int $rankId, bool $appendUsers = false) : Rank {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['*'])
            ->where('id', $rankId)
            ->addRelation('role');

        if($appendUsers) {
            $searchCriteria = $searchCriteria->addRelation('users');
        }

        return $this->getOneBy($searchCriteria, true, false);
    }

    /**
     * //TODO Edit Ares Core - DataObjectManagerFactory make Manager be able to access to SchemaBuilder
     *  to retrieve list of columns safely.
     * 
     * @return array
     */
    public function getListOfColumns() : array {
        $searchCriteria = $this->getDataObjectManager()
            ->getConnection()
            ->select('SHOW COLUMNS FROM `permissions`');
            
        return $searchCriteria;
    }
}
