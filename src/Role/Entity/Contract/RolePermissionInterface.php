<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
