<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Vote\Entity\Contract;

/**
 * Interface VoteInterface
 *
 * @package Ares\Vote\Entity\Contract
 */
interface VoteInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_ENTITY_ID = 'entity_id';
    public const COLUMN_VOTE_ENTITY = 'vote_entity';
    public const COLUMN_VOTE_TYPE = 'vote_type';
}
