<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserCurrency;

/**
 * Class UserCurrencyRepository
 *
 * @package Ares\User\Repository
 */
class UserCurrencyRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_USER_CURRENCY_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_CURRENCY_COLLECTION_';

    /** @var string */
    protected string $entity = UserCurrency::class;

    /**
     * @return Collection
     *
     * @throws DataObjectManagerException
     */
    public function getTopDiamonds(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('user')
            ->orderBy('amount', 'DESC')
            ->where('type', 5)
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @return Collection
     *
     * @throws DataObjectManagerException
     */
    public function getTopDuckets(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('user')
            ->orderBy('amount', 'DESC')
            ->where('type', 0)
            ->limit(3);

        return $this->getList($searchCriteria);
    }

    /**
     * @param int $userId
     * @param int $type
     *
     * @return Collection|null
     */
    public function getUserCurrency(int $userId, int $type): ?Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'user_id' => $userId,
                'type' => $type
            ]);

        return $this->getList($searchCriteria);
    }
}
