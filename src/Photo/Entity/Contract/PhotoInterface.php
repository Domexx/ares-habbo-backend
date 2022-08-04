<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
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
