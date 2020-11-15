<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Entity\Contract;

/**
 * Interface ThreadInterface
 *
 * @package Ares\Forum\Entity\Contract
 */
interface ThreadInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_TOPIC_ID = 'topic_id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_SLUG = 'slug';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_CONTENT = 'content';
    public const COLUMN_LIKES = 'likes';
    public const COLUMN_DISLIKES = 'dislikes';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
