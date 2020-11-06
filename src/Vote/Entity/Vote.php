<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
     * @param int $user_id
     *
     * @return Vote
     */
    public function setUserId(int $user_id): Vote
    {
        return $this->setData(VoteInterface::COLUMN_USER_ID, $user_id);
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->getData(VoteInterface::COLUMN_ENTITY_ID);
    }

    /**
     * @param int $entity_id
     *
     * @return Vote
     */
    public function setEntityId(int $entity_id): Vote
    {
        return $this->setData(VoteInterface::COLUMN_ENTITY_ID, $entity_id);
    }

    /**
     * @return int
     */
    public function getVoteEntity(): int
    {
        return $this->getData(VoteInterface::COLUMN_VOTE_ENTITY);
    }

    /**
     * @param int $vote_entity
     *
     * @return Vote
     */
    public function setVoteEntity(int $vote_entity): Vote
    {
        return $this->setData(VoteInterface::COLUMN_VOTE_ENTITY, $vote_entity);
    }

    /**
     * @return int
     */
    public function getVoteType(): int
    {
        return $this->getData(VoteInterface::COLUMN_VOTE_TYPE);
    }

    /**
     * @param int $vote_type
     *
     * @return Vote
     */
    public function setVoteType(int $vote_type): Vote
    {
        return $this->setData(VoteInterface::COLUMN_VOTE_TYPE, $vote_type);
    }
}
