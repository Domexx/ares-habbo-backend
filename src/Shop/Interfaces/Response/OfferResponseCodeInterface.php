<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Shop\Interfaces\Response;

use Ares\Framework\Interfaces\CustomResponseCodeInterface;

/**
 * Interface OfferResponseCodeInterface
 *
 * @package Ares\Shop\Interfaces\Response
 */
interface OfferResponseCodeInterface extends CustomResponseCodeInterface
{
    /** @var int */
    public const RESPONSE_OFFER_CREATION_FAILED = 13455;

    /** @var int */
    public const RESPONSE_OFFER_NOT_DELETED = 13456;
}
