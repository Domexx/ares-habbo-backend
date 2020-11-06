<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guestbook\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guestbook\Entity\Guestbook;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class GuestbookRepository
 *
 * @package Ares\Guestbook\Repository
 */
class GuestbookRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_GUESTBOOK_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_GUESTBOOK_COLLECTION_';

    /** @var string */
    protected string $entity = Guestbook::class;

    /**
     * @param int $profileId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedProfileEntries(int $profileId, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('profile_id', $profileId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $guildId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedGuildEntries(int $guildId, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('guild_id', $guildId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
