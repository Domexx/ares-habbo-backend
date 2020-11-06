<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Entity\Contract;

/**
 * Interface MessengerFriendshipInterface
 *
 * @package Ares\Messenger\Entity\Contract
 */
interface MessengerFriendshipInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ONE_ID = 'user_one_id';
    public const COLUMN_USER_TWO_ID = 'user_two_id';
    public const COLUMN_RELATION = 'relation';
    public const COLUMN_FRIENDS_SINCE = 'friends_since';
}
