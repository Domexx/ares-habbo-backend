<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\GuildMember;

/**
 * Class GuildMemberRepository
 *
 * @package Ares\Guild\Repository
 */
class GuildMemberRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_GUILD_MEMBER_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_GUILD_MEMBER_COLLECTION_';

    /** @var string */
    protected string $entity = GuildMember::class;

    /**
     * @param int $profileId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedProfileGuilds(int $profileId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['guilds_members.user_id', 'guilds_members.guild_id'])
            ->leftJoin(
                'guilds',
                'guilds_members.guild_id',
                '=',
                'guilds.id'
            )->where('guilds_members.user_id', $profileId)
            ->orderBy('guilds.id', 'DESC')
            ->addRelation('guilds');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $guildId
     *
     * @return int
     */
    public function getGuildMemberCount(int $guildId): int
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('guild_id', $guildId);

        return $this->getList($searchCriteria)->count();
    }

    /**
     * @param int $guildId
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedGuildMembers(int $guildId, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('guild_id', $guildId)
            ->orderBy('level_id', 'ASC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
