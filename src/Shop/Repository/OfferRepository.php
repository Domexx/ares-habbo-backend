<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Shop\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Shop\Entity\Offer;

/**
 * Class OfferRepository
 *
 * @package Ares\Shop\Repository
 */
class OfferRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_OFFER_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_OFFER_COLLECTION_';

    /** @var string */
    protected string $entity = Offer::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedOfferList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
