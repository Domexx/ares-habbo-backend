<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Entity\Contract;

/**
 * interface TopicInterface
 *
 * @package Ares\Forum\Entity\Contract
 */
interface TopicInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_SLUG = 'slug';
    public const COLUMN_DESCRIPTION = 'description';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
