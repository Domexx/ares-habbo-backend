<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Entity\Contract;

/**
 * Interface GuildMemberInterface
 *
 * @package Ares\Guild\Entity\Contract
 */
interface GuildMemberInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_GUILD_ID = 'guild_id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_LEVEL_ID = 'level_id';
    public const COLUMN_MEMBER_SINCE = 'member_since';
}
