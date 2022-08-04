<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Setting\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface SettingResponseCodeInterface
 *
 * @package Ares\Setting\Interfaces\Response
 */
interface SettingResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_SETTING_NOT_FOUND = 12404;
}