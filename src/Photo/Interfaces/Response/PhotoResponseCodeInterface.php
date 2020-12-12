<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Photo\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface PhotoResponseCodeInterface
 *
 * @package Ares\Photo\Interfaces\Response
 */
interface PhotoResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_PHOTO_NOT_DELETED = 10776;
}
