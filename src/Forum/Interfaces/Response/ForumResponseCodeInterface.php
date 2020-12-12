<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface ForumResponseCodeInterface
 *
 * @package Ares\Forum\Interfaces\Response
 */
interface ForumResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_FORUM_COMMENT_NOT_DELETED = 10958;

    /** @var int */
    public const RESPONSE_FORUM_THREAD_NOT_DELETED = 10985;

    /** @var int */
    public const RESPONSE_FORUM_THREAD_THREAD_EXISTS_OR_NO_TOPIC = 11012;

    /** @var int */
    public const RESPONSE_FORUM_TOPIC_NOT_DELETED = 11039;

    /** @var int */
    public const RESPONSE_FORUM_TOPIC_ALREADY_EXIST = 11066;
}
