<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\GuildMember;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedProfileGuilds(int $profileId, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['user_id', 'guild_id'])
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
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedGuildMembers(int $guildId, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('guild_id', $guildId)
            ->orderBy('level_id', 'ASC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }
}
