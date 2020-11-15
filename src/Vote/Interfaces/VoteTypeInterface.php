<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Vote\Interfaces;

/**
 * Interface VoteTypeInterface
 *
 * @package Ares\Vote\Interfaces
 */
interface VoteTypeInterface
{
    /** @var int */
    public const VOTE_LIKE = 1;
    /** @var int */
    public const VOTE_DISLIKE = 0;
}
