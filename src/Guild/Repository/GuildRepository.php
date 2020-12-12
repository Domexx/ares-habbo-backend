<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guild\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Guild\Entity\Guild;

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
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function searchGuilds(string $term, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'guilds.id', 'guilds.user_id', 'guilds.name','guilds.description',
                'guilds.room_id','guilds.badge','guilds.date_created',
            ])->selectRaw('count(guilds_members.guild_id) as member_count')
            ->leftJoin(
                'guilds_members',
                'guilds.id',
                '=',
                'guilds_members.guild_id'
            )->where('guilds.name', 'LIKE', '%'.$term.'%')
            ->groupBy('guilds.id')
            ->orderBy('member_count', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @return Guild|null
     * @throws NoSuchEntityException
     */
    public function getMostMemberGuild(): ?Guild
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'guilds.id', 'guilds.user_id', 'guilds.name','guilds.description',
                'guilds.room_id','guilds.badge','guilds.date_created',
            ])->selectRaw('count(guilds_members.guild_id) as member_count')
            ->leftJoin(
                'guilds_members',
                'guilds.id',
                '=',
                'guilds_members.guild_id'
            )->groupBy('guilds.id')
            ->orderBy('member_count', 'DESC')
            ->limit(1);

        return $this->getOneBy($searchCriteria);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedGuildList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'guilds.id', 'guilds.user_id', 'guilds.name', 'guilds.description',
                'guilds.room_id','guilds.badge','guilds.date_created',
            ])->selectRaw('count(guilds_members.guild_id) as member_count')
            ->leftJoin(
                'guilds_members',
                'guilds_members.guild_id',
                '=',
                'guilds.id'
            )->groupBy('guilds.id')
            ->orderBy('guilds.id')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $id
     *
     * @return Guild|null
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function getGuild(int $id): ?Guild
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'guilds.id', 'guilds.user_id', 'guilds.name','guilds.description',
                'guilds.room_id','guilds.badge','guilds.date_created',
            ])->selectRaw('count(guilds_members.guild_id) as member_count')
            ->where('guilds.id', $id)
            ->join(
                'guilds_members',
                'guilds.id',
                '=',
                'guilds_members.guild_id'
            )->groupBy('guilds.id')
            ->addRelation('user')
            ->addRelation('room')
            ->limit(1);

        return $this->getOneBy($searchCriteria);
    }
}
