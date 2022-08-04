<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rank\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface RankResponseCodeInterface
 *
 * @package Ares\Rank\Interfaces\Response
 */
interface RankResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_RANK_ALREADY_EXIST = 10606;
}
