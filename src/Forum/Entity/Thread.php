<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Entity;

use Ares\Forum\Entity\Contract\ThreadInterface;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Thread
 *
 * @package Ares\Forum\Entity
 */
class Thread extends DataObject implements ThreadInterface
{
    /** @var string */
    public const TABLE = 'ares_forum_threads';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(ThreadInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Thread
     */
    public function setId(int $id): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getTopicId(): int
    {
        return $this->getData(ThreadInterface::COLUMN_TOPIC_ID);
    }

    /**
     * @param int $topicId
     *
     * @return Thread
     */
    public function setTopicId(int $topicId): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_TOPIC_ID, $topicId);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(ThreadInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Thread
     */
    public function setUserId(int $userId): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->getData(ThreadInterface::COLUMN_SLUG);
    }

    /**
     * @param string $slug
     *
     * @return Thread
     */
    public function setSlug(string $slug): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_SLUG, $slug);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(ThreadInterface::COLUMN_TITLE);
    }

    /**
     * @param string $title
     *
     * @return Thread
     */
    public function setTitle(string $title): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(ThreadInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Thread
     */
    public function setDescription(string $description): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getData(ThreadInterface::COLUMN_CONTENT);
    }

    /**
     * @param string $content
     *
     * @return Thread
     */
    public function setContent(string $content): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_CONTENT, $content);
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->getData(ThreadInterface::COLUMN_LIKES);
    }

    /**
     * @param int $likes
     *
     * @return Thread
     */
    public function setLikes(int $likes): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_LIKES, $likes);
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->getData(ThreadInterface::COLUMN_DISLIKES);
    }

    /**
     * @param int $dislikes
     *
     * @return Thread
     */
    public function setDislikes(int $dislikes): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_DISLIKES, $dislikes);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(ThreadInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Thread
     */
    public function setCreatedAt(\DateTime $createdAt): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(ThreadInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Thread
     */
    public function setUpdatedAt(\DateTime $updatedAt): Thread
    {
        return $this->setData(ThreadInterface::COLUMN_UPDATED_AT, $updatedAt);
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

        /** @var ThreadRepository $threadRepository */
        $threadRepository = repository(ThreadRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $threadRepository->getOneToOne(
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
     * @return Thread
     */
    public function setUser(User $user): Thread
    {
        return $this->setData('user', $user);
    }
}
