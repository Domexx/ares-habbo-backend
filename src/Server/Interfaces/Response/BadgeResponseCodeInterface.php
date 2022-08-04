<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Server\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface BadgeResponseCodeInterface
 *
 * @package Ares\Role\Interfaces\Response
 */
interface BadgeResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const BADGE_ALREADY_EXISTS = 10704;

    /** @var int */
    public const BADGE_FOLDER_ERROR = 10705;
}
