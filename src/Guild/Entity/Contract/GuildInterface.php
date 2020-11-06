<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Entity\Contract;

/**
 * Interface GuildInterface
 *
 * @package Ares\Guild\Entity\Contract
 */
interface GuildInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_NAME = 'name';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_ROOM_ID = 'room_id';
    public const COLUMN_STATE = 'state';
    public const COLUMN_BADGE = 'badge';
    public const COLUMN_DATE_CREATED = 'date_created';
}
