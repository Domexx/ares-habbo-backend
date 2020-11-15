<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Photo\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Photo\Entity\Contract\PhotoInterface;
use Ares\Photo\Repository\PhotoRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Photo
 *
 * @package Ares\Photo\Entity
 */
class Photo extends DataObject implements PhotoInterface
{
    /** @var string */
    public const TABLE = 'camera_web';

    /** @var array **/
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(PhotoInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Photo
     */
    public function setId(int $id): Photo
    {
        return $this->setData(PhotoInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(PhotoInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Photo
     */
    public function setUserId(int $userId): Photo
    {
        return $this->setData(PhotoInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getRoomId(): int
    {
        return $this->getData(PhotoInterface::COLUMN_ROOM_ID);
    }

    /**
     * @param int $roomId
     *
     * @return Photo
     */
    public function setRoomId(int $roomId): Photo
    {
        return $this->setData(PhotoInterface::COLUMN_ROOM_ID, $roomId);
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->getData(PhotoInterface::COLUMN_TIMESTAMP);
    }

    /**
     * @param int $timestamp
     *
     * @return Photo
     */
    public function setTimestamp(int $timestamp): Photo
    {
        return $this->setData(PhotoInterface::COLUMN_TIMESTAMP, $timestamp);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->getData(PhotoInterface::COLUMN_URL);
    }

    /**
     * @param string $url
     *
     * @return Photo
     */
    public function setUrl(string $url): Photo
    {
        return $this->setData(PhotoInterface::COLUMN_URL, $url);
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->getData(PhotoInterface::COLUMN_LIKES);
    }

    /**
     * @param int $likes
     *
     * @return Photo
     */
    public function setLikes(int $likes): Photo
    {
        return $this->setData(PhotoInterface::COLUMN_LIKES, $likes);
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->getData(PhotoInterface::COLUMN_DISLIKES);
    }

    /**
     * @param int $dislikes
     *
     * @return Photo
     */
    public function setDislikes(int $dislikes): Photo
    {
        return $this->setData(PhotoInterface::COLUMN_DISLIKES, $dislikes);
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

        /** @var PhotoRepository $photoRepository */
        $photoRepository = repository(PhotoRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $photoRepository->getOneToOne(
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
     * @return Photo
     */
    public function setUser(User $user): Photo
    {
        return $this->setData('user', $user);
    }
}
