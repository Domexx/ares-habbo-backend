<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Room\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Repository\GuildRepository;
use Ares\Room\Entity\Contract\RoomInterface;
use Ares\Room\Repository\RoomRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Room
 *
 * @package Ares\Room\Entity
 */
class Room extends DataObject implements RoomInterface
{
    /** @var string */
    public const TABLE = 'rooms';

    /** @var array **/
    public const RELATIONS = [
        'guild' => 'getGuild',
        'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(RoomInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Room
     */
    public function setId(int $id): Room
    {
        return $this->setData(RoomInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->getData(RoomInterface::COLUMN_OWNER_ID);
    }

    /**
     * @param int $ownerId
     *
     * @return Room
     */
    public function setOwnerId(int $ownerId): Room
    {
        return $this->setData(RoomInterface::COLUMN_OWNER_ID, $ownerId);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(RoomInterface::COLUMN_NAME);
    }

    /**
     * @param string $name
     *
     * @return Room
     */
    public function setName(string $name): Room
    {
        return $this->setData(RoomInterface::COLUMN_NAME, $name);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(RoomInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Room
     */
    public function setDescription(string $description): Room
    {
        return $this->setData(RoomInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->getData(RoomInterface::COLUMN_STATE);
    }

    /**
     * @param string $state
     *
     * @return Room
     */
    public function setState(string $state): Room
    {
        return $this->setData(RoomInterface::COLUMN_STATE, $state);
    }

    /**
     * @return int
     */
    public function getUsers(): int
    {
        return $this->getData(RoomInterface::COLUMN_USERS);
    }

    /**
     * @param int $users
     *
     * @return Room
     */
    public function setUsers(int $users): Room
    {
        return $this->setData(RoomInterface::COLUMN_USERS, $users);
    }

    /**
     * @return int
     */
    public function getUsersMax(): int
    {
        return $this->getData(RoomInterface::COLUMN_USERS_MAX);
    }

    /**
     * @param int $usersMax
     *
     * @return Room
     */
    public function setUsersMax(int $usersMax): Room
    {
        return $this->setData(RoomInterface::COLUMN_USERS_MAX, $usersMax);
    }

    /**
     * @return int
     */
    public function getGuildId(): int
    {
        return $this->getData(RoomInterface::COLUMN_GUILD_ID);
    }

    /**
     * @param int $guildId
     *
     * @return Room
     */
    public function setGuildId(int $guildId): Room
    {
        return $this->setData(RoomInterface::COLUMN_GUILD_ID, $guildId);
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->getData(RoomInterface::COLUMN_SCORE);
    }

    /**
     * @param int $score
     *
     * @return Room
     */
    public function setScore(int $score): Room
    {
        return $this->setData(RoomInterface::COLUMN_SCORE, $score);
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

        /** @var RoomRepository $roomRepository */
        $roomRepository = repository(RoomRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $roomRepository->getOneToOne(
            $userRepository,
            $this->getOwnerId(),
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
     * @return Room
     */
    public function setUser(User $user): Room
    {
        return $this->setData('user', $user);
    }

    /**
     * @return Guild|null
     *
     * @throws DataObjectManagerException
     */
    public function getGuild(): ?Guild
    {
        $guild = $this->getData('guild');

        if ($guild) {
            return $guild;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var RoomRepository $roomRepository */
        $roomRepository = repository(RoomRepository::class);

        /** @var GuildRepository $guildRepository */
        $guildRepository = repository(GuildRepository::class);

        /** @var Guild $guild */
        $guild = $roomRepository->getOneToOne(
            $guildRepository,
            $this->getGuildId(),
            'id'
        );

        if (!$guild) {
            return null;
        }

        $this->setGuild($guild);

        return $guild;
    }

    public function setGuild(Guild $guild): Room
    {
        return $this->setData('guild', $guild);
    }
}
