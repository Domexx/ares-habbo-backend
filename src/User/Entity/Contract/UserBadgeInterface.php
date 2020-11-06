<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity\Contract;

/**
 * Interface UserBadgeInterface
 *
 * @package Ares\User\Entity\Contract
 */
interface UserBadgeInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_SLOT_ID = 'slot_id';
    public const COLUMN_BADGE_CODE = 'badge_code';
}
