<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Guild\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Guild\Entity\Contract\GuildInterface;
use Ares\Guild\Repository\GuildRepository;
use Ares\Room\Entity\Room;
use Ares\Room\Repository\RoomRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Guild
 *
 * @package Ares\Guild\Entity
 */
class Guild extends DataObject implements GuildInterface
{
    /** @var string */
    public const TABLE = 'guilds';

    /*** @var array */
    public const RELATIONS = [
        'user' => 'getUser',
        'room' => 'getRoom'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(GuildInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Guild
     */
    public function setId(int $id): Guild
    {
        return $this->setData(GuildInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->getData(GuildInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Guild
     */
    public function setUserId(int $userId): Guild
    {
        return $this->setData(GuildInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getData(GuildInterface::COLUMN_NAME);
    }

    /**
     * @param string $name
     *
     * @return Guild
     */
    public function setName(string $name): Guild
    {
        return $this->setData(GuildInterface::COLUMN_NAME, $name);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(GuildInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Guild
     */
    public function setDescription(string $description): Guild
    {
        return $this->setData(GuildInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return int
     */
    public function getRoomId(): ?int
    {
        return $this->getData(GuildInterface::COLUMN_ROOM_ID);
    }

    /**
     * @param int $roomId
     *
     * @return Guild
     */
    public function setRoomId(int $roomId): Guild
    {
        return $this->setData(GuildInterface::COLUMN_ROOM_ID, $roomId);
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->getData(GuildInterface::COLUMN_STATE);
    }

    /**
     * @param int $state
     *
     * @return Guild
     */
    public function setState(int $state): Guild
    {
        return $this->setData(GuildInterface::COLUMN_STATE, $state);
    }

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->getData(GuildInterface::COLUMN_BADGE);
    }

    /**
     * @param string $badge
     *
     * @return Guild
     */
    public function setBadge(string $badge): Guild
    {
        return $this->setData(GuildInterface::COLUMN_BADGE, $badge);
    }

    /**
     * @return int
     */
    public function getDateCreated(): int
    {
        return $this->getData(GuildInterface::COLUMN_DATE_CREATED);
    }

    /**
     * @param int $dateCreated
     *
     * @return Guild
     */
    public function setDateCreated(int $dateCreated): Guild
    {
        return $this->setData(GuildInterface::COLUMN_DATE_CREATED, $dateCreated);
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

        /** @var GuildRepository $guildRepository */
        $guildRepository = repository(GuildRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $guildRepository->getOneToOne(
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
     * @return Guild
     */
    public function setUser(User $user): Guild
    {
        return $this->setData('user', $user);
    }

    /**
     * @return Room|null
     *
     * @throws DataObjectManagerException
     */
    public function getRoom(): ?Room
    {
        /** @var Room $room */
        $room = $this->getData('room');

        if ($room) {
            return $room;
        }

        if (!isset($this)) {
            return null;
        }

        /** @var GuildRepository $guildRepository */
        $guildRepository = repository(GuildRepository::class);

        /** @var RoomRepository $roomRepository */
        $roomRepository = repository(RoomRepository::class);

        /** @var Room $room */
        $room = $guildRepository->getOneToOne(
            $roomRepository,
            $this->getRoomId(),
            'id'
        );

        if (!$room) {
            return null;
        }

        $this->setRoom($room);

        return $room;
    }

    /**
     * @param Room $room
     *
     * @return Guild
     */
    public function setRoom(Room $room): Guild
    {
        return $this->setData('room', $room);
    }
}
