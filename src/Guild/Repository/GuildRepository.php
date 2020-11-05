<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\SearchCriteriaInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\Guild;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class GuildRepository
 *
 * @package Ares\Guild\Repository
 */
class GuildRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_GUILD_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_GUILD_COLLECTION_';

    /** @var string */
    protected string $entity = Guild::class;

    /**
     * @param string $term
     * @param int    $page
     * @param int    $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function searchGuilds(string $term, int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->selectRaw(
                'guilds.id, guilds.name, guilds.description, guilds.badge, guilds.date_created, 
                count(guilds_members.guild_id) as member_count'
            )->leftJoin(
                'guilds_members',
                'guilds.id',
                '=',
                'guilds_members.guild_id'
            )->where('guilds.name', 'LIKE', '%'.$term.'%')
            ->orderBy('member_count', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @return Guild|null
     */
    public function getMostMemberGuild(): ?Guild
    {
        $searchCriteria = $this->getDataObjectManager()
            ->selectRaw(
                'guilds.id, guilds.name, guilds.description, guilds.badge, guilds.date_created, 
                count(guilds_members.guild_id) as member_count'
            )->leftJoin(
                'guilds_members',
                'guilds.id',
                '=',
                'guilds_members.guild_id'
            )->orderBy('member_count', 'DESC');

        return $this->getList($searchCriteria)->first();
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return LengthAwarePaginator
     * @throws DataObjectManagerException
     */
    public function getPaginatedGuildList(int $page, int $resultPerPage): LengthAwarePaginator
    {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('user')
            ->addRelation('room')
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $id
     *
     * @return mixed
     * @throws DataObjectManagerException
     */
    public function getGuild(int $id): ?Guild
    {
        $searchCriteria = $this->getDataObjectManager()
            ->selectRaw(
                'guilds.id, guilds.user_id, guilds.name, guilds.description, guilds.room_id,
                 guilds.badge, guilds.state, guilds.date_created, (guilds_members.guild_id) as member_count'
            )->leftJoin(
                'guilds_members',
                'guilds.id',
                '=',
                'guilds_members.guild_id'
            )->where('guilds.id', $id)
            ->addRelation('user')
            ->addRelation('room');

        return $this->getList($searchCriteria)->first();
    }
}
