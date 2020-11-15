<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Guild\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Framework\Model\Query\Collection;
use Ares\Guild\Entity\Contract\GuildMemberInterface;
use Ares\Guild\Repository\GuildMemberRepository;
use Ares\Guild\Repository\GuildRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class GuildMember
 *
 * @package Ares\Guild\Entity
 */
class GuildMember extends DataObject implements GuildMemberInterface
{
    /** @var string */
    public const TABLE = 'guilds_members';

    /*** @var array */
    public const RELATIONS = [
        'user' => 'getUser',
        'guilds' => 'getGuilds'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return GuildMember
     */
    public function setId(int $id): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getGuildId(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_GUILD_ID);
    }

    /**
     * @param int $guild_id
     *
     * @return GuildMember
     */
    public function setGuildId(int $guild_id): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_GUILD_ID, $guild_id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return GuildMember
     */
    public function setUserId(int $userId): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getLevelId(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_LEVEL_ID);
    }

    /**
     * @param int $level_id
     *
     * @return GuildMember
     */
    public function setLevelId(int $level_id): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_LEVEL_ID, $level_id);
    }

    /**
     * @return int
     */
    public function getMemberSince(): int
    {
        return $this->getData(GuildMemberInterface::COLUMN_MEMBER_SINCE);
    }

    /**
     * @param int $member_since
     *
     * @return GuildMember
     */
    public function setMemberSince(int $member_since): GuildMember
    {
        return $this->setData(GuildMemberInterface::COLUMN_MEMBER_SINCE, $member_since);
    }

    /**
     * @return User|null
     *
     * @throws DataObjectManagerException
     */
    public function getUser(): ?User
    {
        /** @var User $user */
        $user = $this->getData('user');

        if ($user) {
            return $user;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var GuildMemberRepository $guildMemberRepository */
        $guildMemberRepository = repository(GuildMemberRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $guildMemberRepository->getOneToOne(
            $userRepository,
            $this->getUserId(),
            'id'
        );

        if (!$user) {
            return null;
        }

        $this->setUser($user);

        return $user;
    }

    /**
     * @param User $user
     *
     * @return GuildMember
     */
    public function setUser(User $user): GuildMember
    {
        return $this->setData('user', $user);
    }

    /**
     * @return Collection|array|mixed|null
     * @throws DataObjectManagerException
     */
    public function getGuilds(): ?Collection
    {
        $guilds = $this->getData('guilds');

        if ($guilds) {
            return $guilds;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var GuildMemberRepository $guildMemberRepository */
        $guildMemberRepository = repository(GuildMemberRepository::class);

        /** @var GuildRepository $guildRepository */
        $guildRepository = repository(GuildRepository::class);

        $guilds = $guildMemberRepository->getOneToMany(
            $guildRepository,
            $this->getGuildId(),
            'id'
        );

        if (!$guilds->toArray()) {
            return null;
        }

        $this->setGuilds($guilds);

        return $guilds;
    }

    /**
     * @param Collection $guild
     *
     * @return GuildMember
     */
    public function setGuilds(Collection $guild): GuildMember
    {
        return $this->setData('guild', $guild);
    }
}
