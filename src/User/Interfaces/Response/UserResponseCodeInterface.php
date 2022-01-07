<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface UserResponseCodeInterface
 *
 * @package Ares\User\Interfaces\Response
 */
interface UserResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_AUTH_LOGIN_FAILED = 10020;

    /** @var int */
    public const RESPONSE_AUTH_LOGIN_BANNED = 10047;

    /** @var int */
    public const RESPONSE_AUTH_REGISTER_EXCEEDED = 10074;

    /** @var int */
    public const RESPONSE_AUTH_REGISTER_GENDER = 10101;

    /** @var int */
    public const RESPONSE_AUTH_REGISTER_TAKEN = 10128;

    /** @var int */
    public const RESPONSE_AUTH_REGISTER_NO_VIABLE_LOOKS = 10398;

    /** @var int */
    public const RESPONSE_USER_NOT_FOUND = 10153;

    /** @var int */
    public const RESPONSE_CURRENCY_NOT_FOUND = 10155;

    /** @var int */
    public const RESPONSE_CURRENCY_NOT_UPDATED = 10182;

    /** @var int */
    public const RESPONSE_GIFT_ALREADY_PICKED = 10209;

    /** @var int */
    public const RESPONSE_SETTINGS_OLD_NOT_EQUALS_NEW = 10236;

    /** @var int */
    public const RESPONSE_SETTINGS_DIFFERENT_EMAIL = 10263;

    /** @var int */
    public const RESPONSE_SETTINGS_USER_EMAIL_EXISTS = 10290;

    /** @var int */
    public const RESPONSE_SETTINGS_DIFFERENT_PASSWORD = 10317;

    /** @var int */
    public const RESPONSE_SETTINGS_NOT_ALLOWED_TO_CHANGE_USERNAME = 10344;

    /** @var int */
    public const RESPONSE_SETTINGS_USER_USERNAME_EXISTS = 10371;
}
