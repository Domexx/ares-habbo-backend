<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity\Contract\Gift;

/**
 * Interface DailyGiftInterface
 *
 * @package Ares\User\Entity\Contract\Gift
 */
interface DailyGiftInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_AMOUNT = 'amount';
    public const COLUMN_PICK_TIME = 'pick_time';
}
