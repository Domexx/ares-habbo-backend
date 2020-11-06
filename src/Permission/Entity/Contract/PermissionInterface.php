<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Permission\Entity\Contract;

/**
 * Interface PermissionInterface
 *
 * @package Ares\Permission\Entity\Contract
 */
interface PermissionInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_RANK_NAME = 'rank_name';
    public const COLUMN_BADGE = 'badge';
    public const COLUMN_LEVEL = 'level';
    public const COLUMN_PREFIX = 'prefix';
    public const COLUMN_PREFIX_COLOR = 'prefix_color';
}
