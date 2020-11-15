<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guestbook\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Guestbook\Entity\Contract\GuestbookInterface;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Guestbook
 *
 * @package Ares\Guestbook\Entity
 */
class Guestbook extends DataObject implements GuestbookInterface
{
    /** @var string */
    public const TABLE = 'ares_guestbook';

    /** @var array **/
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(GuestbookInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Guestbook
     */
    public function setId(int $id): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(GuestbookInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Guestbook
     */
    public function setUserId(int $userId): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getProfileId(): ?int
    {
        return $this->getData(GuestbookInterface::COLUMN_PROFILE_ID);
    }

    /**
     * @param int $profileId
     *
     * @return Guestbook
     */
    public function setProfileId(?int $profileId): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_PROFILE_ID, $profileId);
    }

    /**
     * @return int
     */
    public function getGuildId(): ?int
    {
        return $this->getData(GuestbookInterface::COLUMN_GUILD_ID);
    }

    /**
     * @param int|null $guildId
     *
     * @return Guestbook
     */
    public function setGuildId(?int $guildId): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_GUILD_ID, $guildId);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getData(GuestbookInterface::COLUMN_CONTENT);
    }

    /**
     * @param string $content
     *
     * @return Guestbook
     */
    public function setContent(string $content): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_CONTENT, $content);
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->getData(GuestbookInterface::COLUMN_LIKES);
    }

    /**
     * @param int $likes
     *
     * @return Guestbook
     */
    public function setLikes(int $likes): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_LIKES, $likes);
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->getData(GuestbookInterface::COLUMN_DISLIKES);
    }

    /**
     * @param int $dislikes
     *
     * @return Guestbook
     */
    public function setDislikes(int $dislikes): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_DISLIKES, $dislikes);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(GuestbookInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Guestbook
     */
    public function setCreatedAt(\DateTime $createdAt): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(GuestbookInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Guestbook
     */
    public function setUpdatedAt(\DateTime $updatedAt): Guestbook
    {
        return $this->setData(GuestbookInterface::COLUMN_UPDATED_AT, $updatedAt);
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

        /** @var GuestbookRepository $guestBookRepository */
        $guestBookRepository = repository(GuestbookRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $guestBookRepository->getOneToOne(
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
     * @return Guestbook
     */
    public function setUser(User $user): Guestbook
    {
        return $this->setData('user', $user);
    }
}
