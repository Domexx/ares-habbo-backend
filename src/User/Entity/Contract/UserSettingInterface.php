<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\User\Entity\Contract;

/**
 * Interface UserSettingInterface
 *
 * @package Ares\User\Entity\Contract
 */
interface UserSettingInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_ACHIEVEMENT_SCORE = 'achievement_score';
    public const COLUMN_CAN_CHANGE_NAME = 'can_change_name';
    public const COLUMN_BLOCK_FOLLOWING = 'block_following';
    public const COLUMN_BLOCK_FRIENDREQUESTS = 'block_friendrequests';
    public const COLUMN_BLOCK_ROOMINVITES = 'block_roominvites';
    public const COLUMN_BLOCK_CAMERA_FOLLOW = 'block_camera_follow';
    public const COLUMN_ONLINE_TIME = 'online_time';
    public const COLUMN_BLOCK_ALERTS = 'block_alerts';
    public const COLUMN_IGNORE_BOTS = 'ignore_bots';
    public const COLUMN_IGNORE_PETS = 'ignore_pets';
}
