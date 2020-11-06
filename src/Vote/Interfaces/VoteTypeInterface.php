<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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