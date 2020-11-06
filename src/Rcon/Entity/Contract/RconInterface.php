<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Entity\Contract;

/**
 * Interface RconInterface
 *
 * @package Ares\Rcon\Entity\Contract
 */
interface RconInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_COMMAND = 'command';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_PERMISSION_ID = 'permission_id';
}
