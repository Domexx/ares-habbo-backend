<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Photo\Entity\Contract;

/**
 * Interface PhotoInterface
 *
 * @package Ares\Photo\Entity\Contract
 */
interface PhotoInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_ROOM_ID = 'room_id';
    public const COLUMN_TIMESTAMP = 'timestamp';
    public const COLUMN_URL = 'url';
}
