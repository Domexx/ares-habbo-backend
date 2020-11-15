<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Entity;

use Ares\Article\Entity\Contract\CommentInterface;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Article
 *
 * @package Ares\Article\Entity
 */
class Comment extends DataObject implements CommentInterface
{
    /** @var string */
    public const TABLE = 'ares_articles_comments';

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
    public function getUserId(): int
    {
        return $this->getData(CommentInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Comment
     */
    public function setUserId(int $userId): Comment
    {
        return $this->setData(CommentInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->getData(CommentInterface::COLUMN_ARTICLE_ID);
    }

    /**
     * @param int $article_id
     *
     * @return Comment
     */
    public function setArticleId(int $article_id): Comment
    {
        return $this->setData(CommentInterface::COLUMN_ARTICLE_ID, $article_id);
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
     * @param int $isEdited
     *
     * @return Comment
     */
    public function setIsEdited(int $isEdited): Comment
    {
        return $this->setData(CommentInterface::COLUMN_IS_EDITED, $isEdited);
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
     * @param \DateTime $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt(\DateTime $createdAt): Comment
    {
        return $this->setData(CommentInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(CommentInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Comment
     */
    public function setUpdatedAt(\DateTime $updatedAt): Comment
    {
        return $this->setData(CommentInterface::COLUMN_UPDATED_AT, $updatedAt);
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

        /** @var CommentRepository $commentRepository */
        $commentRepository = repository(CommentRepository::class);

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
