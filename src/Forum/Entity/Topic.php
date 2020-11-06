<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Entity;

use Ares\Forum\Entity\Contract\TopicInterface;
use Ares\Framework\Model\DataObject;

/**
 * Class Topic
 *
 * @package Ares\Forum\Entity
 */
class Topic extends DataObject implements TopicInterface
{
    /** @var string */
    public const TABLE = 'ares_forum_topics';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(TopicInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Topic
     */
    public function setId(int $id): Topic
    {
        return $this->setData(TopicInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(TopicInterface::COLUMN_TITLE);
    }

    /**
     * @param string $title
     *
     * @return Topic
     */
    public function setTitle(string $title): Topic
    {
        return $this->setData(TopicInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->getData(TopicInterface::COLUMN_SLUG);
    }

    /**
     * @param string $slug
     *
     * @return Topic
     */
    public function setSlug(string $slug): Topic
    {
        return $this->setData(TopicInterface::COLUMN_SLUG, $slug);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(TopicInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Topic
     */
    public function setDescription(string $description): Topic
    {
        return $this->setData(TopicInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->getData(TopicInterface::COLUMN_CREATED_AT);
    }

    /**
     * @param \DateTime $created_at
     *
     * @return Topic
     */
    public function setCreatedAt(\DateTime $created_at): Topic
    {
        return $this->setData(TopicInterface::COLUMN_CREATED_AT, $created_at);
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->getData(TopicInterface::COLUMN_UPDATED_AT);
    }

    /**
     * @param \DateTime $updated_at
     *
     * @return Topic
     */
    public function setUpdatedAt(\DateTime $updated_at): Topic
    {
        return $this->setData(TopicInterface::COLUMN_UPDATED_AT, $updated_at);
    }
}
