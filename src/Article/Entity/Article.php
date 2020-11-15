<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Entity;

use Ares\Article\Entity\Contract\ArticleInterface;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class Article
 *
 * @package Ares\Article\Entity
 */
class Article extends DataObject implements ArticleInterface
{
    /** @var string */
    public const TABLE = 'ares_articles';

    /** @var array **/
    public const RELATIONS = [
      'user' => 'getUser'
    ];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(ArticleInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Article
     */
    public function setId(int $id): Article
    {
        return $this->setData(ArticleInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(ArticleInterface::COLUMN_TITLE);
    }

    /**
     * @param string $title
     *
     * @return Article
     */
    public function setTitle(string $title): Article
    {
        return $this->setData(ArticleInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->getData(ArticleInterface::COLUMN_SLUG);
    }

    /**
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug(string $slug): Article
    {
        return $this->setData(ArticleInterface::COLUMN_SLUG, $slug);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(ArticleInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Article
     */
    public function setDescription(string $description): Article
    {
        return $this->setData(ArticleInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->getData(ArticleInterface::COLUMN_CONTENT);
    }

    /**
     * @param string $content
     *
     * @return Article
     */
    public function setContent(string $content)
    {
        return $this->setData(ArticleInterface::COLUMN_CONTENT, $content);
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->getData(ArticleInterface::COLUMN_IMAGE);
    }

    /**
     * @param string $image
     *
     * @return Article
     */
    public function setImage(string $image): Article
    {
        return $this->setData(ArticleInterface::COLUMN_IMAGE, $image);
    }

    /**
     * @return int|null
     */
    public function getAuthorId(): ?int
    {
        return $this->getData(ArticleInterface::COLUMN_AUTHOR_ID);
    }

    /**
     * @param int $authorId
     *
     * @return Article
     */
    public function setAuthorId(int $authorId): Article
    {
        return $this->setData(ArticleInterface::COLUMN_AUTHOR_ID, $authorId);
    }

    /**
     * @return int
     */
    public function getHidden(): int
    {
        return $this->getData(ArticleInterface::COLUMN_HIDDEN);
    }

    /**
     * @param int $hidden
     *
     * @return Article
     */
    public function setHidden(int $hidden): Article
    {
        return $this->setData(ArticleInterface::COLUMN_HIDDEN, $hidden);
    }

    /**
     * @return int
     */
    public function getPinned(): int
    {
        return $this->getData(ArticleInterface::COLUMN_PINNED);
    }

    /**
     * @param int $pinned
     *
     * @return Article
     */
    public function setPinned(int $pinned): Article
    {
        return $this->setData(ArticleInterface::COLUMN_PINNED, $pinned);
    }

    /**
     * @return int
     */
    public function getLikes(): int
    {
        return $this->getData(ArticleInterface::COLUMN_LIKES);
    }

    /**
     * @param int $likes
     *
     * @return Article
     */
    public function setLikes(int $likes): Article
    {
        return $this->setData(ArticleInterface::COLUMN_LIKES, $likes);
    }

    /**
     * @return int
     */
    public function getDislikes(): int
    {
        return $this->getData(ArticleInterface::COLUMN_DISLIKES);
    }

    /**
     * @param int $dislikes
     *
     * @return Article
     */
    public function setDislikes(int $dislikes): Article
    {
        return $this->setData(ArticleInterface::COLUMN_DISLIKES, $dislikes);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(ArticleInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return Article
     */
    public function setCreatedAt(\DateTime $createdAt): Article
    {
        return $this->setData(ArticleInterface::COLUMN_CREATED_AT, $createdAt);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(ArticleInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updatedAt
     *
     * @return Article
     */
    public function setUpdatedAt(\DateTime $updatedAt): Article
    {
        return $this->setData(ArticleInterface::COLUMN_UPDATED_AT, $updatedAt);
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

        /** @var ArticleRepository $articleRepository */
        $articleRepository = repository(ArticleRepository::class);

        /** @var UserRepository $userRepository */
        $userRepository = repository(UserRepository::class);

        /** @var User $user */
        $user = $articleRepository->getOneToOne(
            $userRepository,
            $this->getAuthorId(),
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
     * @return Article
     */
    public function setUser(User $user): Article
    {
        return $this->setData('user', $user);
    }
}
