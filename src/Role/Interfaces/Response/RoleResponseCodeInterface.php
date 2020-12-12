<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface RoleResponseCodeInterface
 *
 * @package Ares\Role\Interfaces\Response
 */
interface RoleResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_ROLE_NOT_DELETED = 10425;

    /** @var int */
    public const RESPONSE_ROLE_NO_RIGHTS_TO_EXECUTE_ACTION = 10479;

    /** @var int */
    public const RESPONSE_ROLE_ALREADY_ASSIGNED_TO_USER = 10506;

    /** @var int */
    public const RESPONSE_ROLE_ALREADY_EXIST = 10604;

    /** @var int */
    public const RESPONSE_ROLE_CYCLE_DETECTED = 10533;

    /** @var int */
    public const RESPONSE_ROLE_PERMISSION_NOT_DELETED = 10452;

    /** @var int */
    public const RESPONSE_ROLE_PERMISSION_ALREADY_EXIST = 10560;

    /** @var int */
    public const RESPONSE_ROLE_PERMISSION_ALREADY_ASSIGNED_TO_ROLE = 10587;
}
