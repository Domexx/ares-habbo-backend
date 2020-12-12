<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rcon\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface RconResponseCodeInterface
 *
 * @package Ares\Rcon\Interfaces\Response
 */
interface RconResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_RCON_NO_RIGHTS_TO_EXECUTE = 10614;

    /** @var int */
    public const RESPONSE_RCON_COULD_NOT_SEND_COMMAND = 10641;

    /** @var int */
    public const RESPONSE_RCON_NO_CONNECTION = 10668;

    /** @var int */
    public const RESPONSE_RCON_COULD_NOT_CREATE_SOCKET = 10695;

    /** @var int */
    public const RESPONSE_RCON_COMMAND_ALREADY_EXIST = 10722;

    /** @var int */
    public const RESPONSE_RCON_COMMAND_NOT_DELETED = 10749;
}
