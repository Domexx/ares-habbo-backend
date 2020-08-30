<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Interfaces;

/**
 * Interface VoteEntityInterface
 *
 * @package Ares\Vote\Interfaces
 */
interface VoteEntityInterface
{
    /** @var int */
    public const ARTICLE_VOTE_ENTITY = 1;
    /** @var int */
    public const ARTICLE_COMMENT_VOTE_ENTITY = 2;
    /** @var int */
    public const FORUM_VOTE_ENTITY = 3;
    /** @var int */
    public const FORUM_COMMENT_VOTE_ENTITY = 4;
    /** @var int */
    public const GUESTBOOK_VOTE_ENTITY = 5;
}
