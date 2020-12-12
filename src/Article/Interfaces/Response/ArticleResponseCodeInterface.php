<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface ArticleResponseCodeInterface
 *
 * @package Ares\Article\Interfaces\Response
 */
interface ArticleResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_ARTICLE_NOT_DELETED = 11093;

    /** @var int */
    public const RESPONSE_ARTICLE_TITLE_EXIST = 11120;

    /** @var int */
    public const RESPONSE_ARTICLE_COMMENT_NOT_DELETED = 11147;

    /** @var int */
    public const RESPONSE_ARTICLE_COMMENT_EXCEEDED = 11174;
}
