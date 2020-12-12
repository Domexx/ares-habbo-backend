<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Payment\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface PaymentResponseCodeInterface
 *
 * @package Ares\Payment\Interfaces\Response
 */
interface PaymentResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_PAYMENT_NOT_DELETED = 10823;

    /** @var int */
    public const RESPONSE_PAYMENT_ALREADY_ONGOING = 10850;
}
