<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Entity\Contract;

/**
 * Interface CommentInterface
 *
 * @package Ares\Article\Entity\Contract
 */
interface CommentInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_ARTICLE_ID = 'article_id';
    public const COLUMN_CONTENT = 'content';
    public const COLUMN_IS_EDITED = 'is_edited';
    public const COLUMN_LIKES = 'likes';
    public const COLUMN_DISLIKES = 'dislikes';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
