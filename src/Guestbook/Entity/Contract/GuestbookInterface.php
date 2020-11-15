<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guestbook\Entity\Contract;

/**
 * Interface GuestbookInterface
 *
 * @package Ares\Guestbook\Entity\Contract
 */
interface GuestbookInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_PROFILE_ID = 'profile_id';
    public const COLUMN_GUILD_ID = 'guild_id';
    public const COLUMN_CONTENT = 'content';
    public const COLUMN_LIKES = 'likes';
    public const COLUMN_DISLIKES = 'dislikes';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
