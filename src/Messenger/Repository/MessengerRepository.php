<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Messenger\Entity\MessengerFriendship;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedMessengerFriends(int $userId, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('user_one_id', $userId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
