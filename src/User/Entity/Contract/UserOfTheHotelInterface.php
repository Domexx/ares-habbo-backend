<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\User\Entity\Contract;

/**
 * Interface UserOfTheHotelInterface
 *
 * @package Ares\User\Entity\Contract
 */
interface UserOfTheHotelInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_TO_TIMESTAMP = 'to_timestamp';
    public const COLUMN_CREATED_AT = 'created_at';
}
