<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Entity\Contract;

/**
 * Interface RoleUserInterface
 *
 * @package Ares\Role\Entity\Contract
 */
interface RoleUserInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_ROLE_ID = 'role_id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_CREATED_AT = 'created_at';
}
