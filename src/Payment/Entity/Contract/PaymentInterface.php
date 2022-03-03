<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Payment\Entity\Contract;

/**
 * Interface PaymentInterface
 *
 * @package Ares\Payment\Entity\Contract
 */
interface PaymentInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_USER_ID = 'user_id';
    public const COLUMN_OFFER_ID = 'offer_id';
    public const COLUMN_ORDER_ID = 'order_id';
    public const COLUMN_PAYER_ID = 'payer_id';
    public const COLUMN_STATUS = 'status';
    public const COLUMN_DELIVERED = 'delivered';
    public const COLUMN_CREATED_AT = 'created_at';
    public const COLUMN_UPDATED_AT = 'updated_at';
}
