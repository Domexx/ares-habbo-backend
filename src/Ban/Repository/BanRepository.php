<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Ban\Repository;

use Ares\Ban\Entity\Ban;
use Ares\Framework\Repository\BaseRepository;

/**
 * Class BanRepository
 *
 * @package Ares\Ban\Repository
 */
class BanRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_BAN_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_BAN_COLLECTION_';

    /** @var string */
    protected string $entity = Ban::class;
}
