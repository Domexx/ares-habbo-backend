<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Messenger\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Messenger\Entity\MessengerFriendship;

/**
 * Class MessengerRepository
 *
 * @package Ares\Messenger\Repository
 */
class MessengerRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_MESSENGER_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_MESSENGER_COLLECTION_';

    /** @var string */
    protected string $entity = MessengerFriendship::class;

    /**
     * @param int $userId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedMessengerFriends(int $userId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'messenger_friendships.id',
                'messenger_friendships.relation',
                'messenger_friendships.friends_since',
                'users.id',
                'users.username',
                'users.motto',
                'users.look',
                'users.online',
                'users.last_online'
            ])->leftJoin(
                'users',
                'messenger_friendships.user_two_id',
                '=',
                'users.id'
            )->where('messenger_friendships.user_one_id', $userId)
            ->orderBy('users.online', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
