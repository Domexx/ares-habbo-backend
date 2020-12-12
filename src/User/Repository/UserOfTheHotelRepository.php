<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserOfTheHotel;

/**
 * Class UserOfTheHotelRepository
 *
 * @package Ares\User\Repository
 */
class UserOfTheHotelRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_UOTH_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_UOTH_COLLECTION_';

    /** @var string */
    protected string $entity = UserOfTheHotel::class;

    /**
     * @return UserOfTheHotel|null
     *
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function getCurrentUser(): ?UserOfTheHotel
    {
        $searchCriteria = $this->getDataObjectManager()
            ->latest()
            ->addRelation('user');

        return $this->getOneBy($searchCriteria, true);
    }
}
