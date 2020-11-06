<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
