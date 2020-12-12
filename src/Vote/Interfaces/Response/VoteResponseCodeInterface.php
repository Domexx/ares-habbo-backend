<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Vote\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface VoteResponseCodeInterface
 *
 * @package Ares\Vote\Interfaces\Response
 */
interface VoteResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_VOTE_ENTITY_COULD_NOT_BE_INCREMENTED = 11201;

    /** @var int */
    public const RESPONSE_VOTE_ENTITY_NOT_DELETED = 11228;

    /** @var int */
    public const RESPONSE_VOTE_ENTITY_REPOSITORY_NOT_FOUND = 11255;

    /** @var int */
    public const RESPONSE_VOTE_ENTITY_ALREADY_VOTED = 11282;

    /** @var int */
    public const RESPONSE_VOTE_ENTITY_NOT_EXIST = 11309;
}
