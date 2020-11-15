<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guestbook\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guestbook\Entity\Guestbook;

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
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedProfileEntries(int $profileId, int $page, int $resultPerPage): PaginatedCollection
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
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedGuildEntries(int $guildId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('guild_id', $guildId)
            ->orderBy('id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
