<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Vote\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Vote\Entity\Contract\VoteInterface;

/**
 * Class Vote
 *
 * @package Ares\Vote\Entity
 */
class Vote extends DataObject implements VoteInterface
{
    /** @var string */
    public const TABLE = 'ares_votes';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(VoteInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Vote
     */
    public function setId(int $id): Vote
    {
        return $this->setData(VoteInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->getData(VoteInterface::COLUMN_USER_ID);
    }

    /**
     * @param int $userId
     *
     * @return Vote
     */
    public function setUserId(int $userId): Vote
    {
        return $this->setData(VoteInterface::COLUMN_USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->getData(VoteInterface::COLUMN_ENTITY_ID);
    }

    /**
     * @param int $entityId
     *
     * @return Vote
     */
    public function setEntityId(int $entityId): Vote
    {
        return $this->setData(VoteInterface::COLUMN_ENTITY_ID, $entityId);
    }

    /**
     * @return int
     */
    public function getVoteEntity(): int
    {
        return $this->getData(VoteInterface::COLUMN_VOTE_ENTITY);
    }

    /**
     * @param int $voteEntity
     *
     * @return Vote
     */
    public function setVoteEntity(int $voteEntity): Vote
    {
        return $this->setData(VoteInterface::COLUMN_VOTE_ENTITY, $voteEntity);
    }

    /**
     * @return int
     */
    public function getVoteType(): int
    {
        return $this->getData(VoteInterface::COLUMN_VOTE_TYPE);
    }

    /**
     * @param int $voteType
     *
     * @return Vote
     */
    public function setVoteType(int $voteType): Vote
    {
        return $this->setData(VoteInterface::COLUMN_VOTE_TYPE, $voteType);
    }
}
