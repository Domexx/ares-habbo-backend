<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Ban\Entity\Contract;

/**
 * Interface BanInterface
 *
 * @package Ares\Ban\Entity\Contract
 */
interface BanInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_IP = 'ip';
    public const COLUMN_MACHINE_ID = 'machine_id';
    public const COLUMN_USER_STAFF_ID = 'user_staff_id';
    public const COLUMN_TIMESTAMP = 'timestamp';
    public const COLUMN_BAN_EXPIRE = 'ban_expire';
    public const COLUMN_BAN_REASON = 'ban_reason';
    public const COLUMN_TYPE = 'type';
    public const COLUMN_CFH_TOPIC = 'cfh_topic';
}
