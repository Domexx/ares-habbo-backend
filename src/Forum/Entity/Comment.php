<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Entity;

use Ares\Forum\Entity\Contract\CommentInterface;
use Ares\Forum\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Comment
 *
 * @package Ares\Forum\Entity
 */
class Comment extends DataObject implements CommentInterface
{
    /** @var string */
    public const TABLE = 'ares_forum_comments';

    /*** @var array */
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(CommentInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Comment
     */
    public function setId(int $id): Comment
    {
        return $this->setData(CommentInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getThreadId(): int
    {
        return $this->getData(CommentInterface::COLUMN_THREAD_ID);
    }

    /**
     * @param int $thread_id
     *
     * @return Comment
     */
    public function setThreadId(int $thread_id): Comment
    {
        return $this->setData(CommentInterface::COLUMN_THREAD_ID, $thread_id);
    }

    /**
     * @return int
     */
    public function getTopicId(): int
    {
        return $this->getData(CommentInterface::COLUMN_TOPIC_ID);
    }

    /**
     * @param int $topic_id
     *
     * @return Comment
     */
    public function setTopicId(int $topic_id): Comment
    {
        return $this->setData(CommentInterface::COLUMN_TOPIC_ID, $topic_id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(CommentInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $user_id
     *
     * @return Comment
     */
    public function setUserId(int $user_id): Comment
    {
        return $this->setData(CommentInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getData(CommentInterface::COLUMN_CONTENT);
    }

    /**
     * @param string $content
     *
     * @return Comment
     */
    public function setContent(string $content): Comment
    {
        return $this->setData(CommentInterface::COLUMN_CONTENT, $content);
    }

    /**
     * @return int
     */
    public function getIsEdited(): int
    {
        return $this->getData(CommentInterface::COLUMN_IS_EDITED);
    }

    /**
     * @param int $is_edited
     *
     * @return Comment
     */
    public function setIsEdited(int $is_edited): Comment
    {
        return $this->setData(CommentInterface::COLUMN_IS_EDITED, $is_edited);
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->getData(CommentInterface::COLUMN_LIKES);
    }

    /**
     * @param int $likes
     *
     * @return Comment
     */
    public function setLikes(int $likes): Comment
    {
        return $this->setData(CommentInterface::COLUMN_LIKES, $likes);
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->getData(CommentInterface::COLUMN_DISLIKES);
    }

    /**
     * @param int $dislikes
     *
     * @return Comment
     */
    public function setDislikes(int $dislikes): Comment
    {
        return $this->setData(CommentInterface::COLUMN_DISLIKES, $dislikes);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(CommentInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $created_at
     *
     * @return Comment
     */
    public function setCreatedAt(\DateTime $created_at): Comment
    {
        return $this->setData(CommentInterface::COLUMN_CREATED_AT, $created_at);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(CommentInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updated_at
     *
     * @return Comment
     */
    public function setUpdatedAt(\DateTime $updated_at): Comment
    {
        return $this->setData(CommentInterface::COLUMN_UPDATED_AT, $updated_at);
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

        /** @var CommentRepository $commentRepository */
        $commentRepository = repository(PhotoRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $commentRepository->getOneToOne(
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
     * @return Comment
     */
    public function setUser(User $user): Comment
    {
        return $this->setData('user', $user);
    }
}
