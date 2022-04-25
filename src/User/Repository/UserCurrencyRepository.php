<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\User\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\UserCurrency;
use Ares\User\Interfaces\UserCurrencyTypeInterface;

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
     * @param int $type
     * @param array $exceptRanks
     * 
     * @return Collection
     *
     * @throws DataObjectManagerException
    */
    public function getTops(int $type, array $exceptRanks = []) : Collection 
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'users.id','users.username','users.mail','users.mail','users.account_created','users.last_login',
                'users.last_online','users.motto','users.look','users.gender','users.rank','users.credits','users.online',
                'users.home_room','users.created_at','users.updated_at','users_currency.type','users_currency.amount'
            ])
            ->join(
                'users',
                'users_currency.user_id',
                '=',
                'users.id'
            )
            ->where('users_currency.type', '=', $type)
            ->whereNotIn('users.rank', $exceptRanks)
            ->orderBy('users_currency.amount', 'DESC')
            ->limit(10);

        return $this->getList($searchCriteria);
    }

    /**
     * @param int $userId
     * @param int $type
     *
     * @return UserCurrency|null
     */
    public function getUserCurrency(int $userId, int $type): ?UserCurrency
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'user_id' => $userId,
                'type' => $type
            ]);

        return $this->getOneBy($searchCriteria);
    }
}
