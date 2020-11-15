<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Rcon\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Rcon\Entity\Rcon;

/**
 * Class RconRepository
 *
 * @package Ares\Rcon\Repository
 */
class RconRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_RCON_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_RCON_COLLECTION_';

    /** @var string */
    protected string $entity = Rcon::class;
}
