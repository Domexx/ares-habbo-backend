<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
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
     */
    public function getCurrentUser(): ?UserOfTheHotel
    {
        $searchCriteria = $this->getDataObjectManager()
            ->latest()
            ->addRelation('user');

        return $this->getList($searchCriteria)->first();
    }
}
