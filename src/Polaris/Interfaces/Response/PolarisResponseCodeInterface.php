<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Polaris\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface PolarisResponseCodeInterface
 *
 * @package Ares\Polaris\Interfaces\Response
 */
interface PolarisResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_POLARIS_ERROR = 8504;
}