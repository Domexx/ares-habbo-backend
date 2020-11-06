<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Entity\Contract;

/**
 * Interface ArticleInterface
 *
 * @package Ares\Article\Entity\Contract
 */
interface ArticleInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_SLUG = 'slug';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_CONTENT = 'content';
    public const COLUMN_IMAGE = 'image';
    public const COLUMN_AUTHOR_ID = 'author_id';
    public const COLUMN_HIDDEN = 'hidden';
    public const COLUMN_PINNED = 'pinned';
    public const COLUMN_LIKES = 'likes';
    public const COLUMN_DISLIKES = 'dislikes';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
