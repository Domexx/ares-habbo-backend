<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guestbook\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface GuestbookResponseCodeInterface
 *
 * @package Ares\Guestbook\Interfaces\Response
 */
interface GuestbookResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_GUESTBOOK_ASSOCIATED_ENTITIES_NOT_FOUND = 10877;

    /** @var int */
    public const RESPONSE_GUESTBOOK_ENTRY_NOT_DELETED = 10904;

    /** @var int */
    public const RESPONSE_GUESTBOOK_USER_EXCEEDED_COMMENTS = 10931;
}
