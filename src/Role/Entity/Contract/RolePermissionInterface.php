<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Entity\Contract;

/**
 * Interface RolePermissionInterface
 *
 * @package Ares\Role\Entity\Contract
 */
interface RolePermissionInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_ROLE_ID = 'role_id';
    public const COLUMN_PERMISSION_ID = 'permission_id';
    public const COLUMN_CREATED_AT = 'created_at';
}
