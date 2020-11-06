<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\User\Entity\Contract;

/**
 * Class UserCurrencyInterface
 *
 * @package Ares\User\Entity\Contract
 */
interface UserCurrencyInterface
{
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_TYPE = 'type';
    public const COLUMN_AMOUNT = 'amount';
}
