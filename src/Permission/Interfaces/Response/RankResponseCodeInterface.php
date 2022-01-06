<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Permission\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface RankResponseCodeInterface
 *
 * @package Ares\Permission\Interfaces\Response
 */
interface RankResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_RANK_ALREADY_EXIST = 10606;
}
