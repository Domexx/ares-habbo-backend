<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Repository\Gift;

use Ares\Framework\Repository\BaseRepository;
use Ares\User\Entity\Gift\DailyGift;

/**
 * Class DailyGiftRepository
 *
 * @package Ares\User\Repository\Gift
 */
class DailyGiftRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_USER_DAILY_GIFT_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_USER_DAILY_GIFT_COLLECTION_';

    /** @var string */
    protected string $entity = DailyGift::class;
}
