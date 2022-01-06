<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Shop\Entity\Contract;

/**
 * Interface OfferInterface
 *
 * @package Ares\Shop\Entity\Contract
 */
interface OfferInterface
{
    public const COLUMN_ID = 'id';
    public const COLUMN_PRICE = 'price';
    public const COLUMN_TITLE = 'title';
    public const COLUMN_IMAGE = 'image';
    public const COLUMN_DATA = 'data';
    public const COLUMN_DESCRIPTION = 'description';
}
