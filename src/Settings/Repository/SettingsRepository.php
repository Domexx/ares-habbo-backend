<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Settings\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Settings\Entity\Setting;

/**
 * Class SettingsRepository
 *
 * @package Ares\Settings\Repository
 */
class SettingsRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_SETTINGS_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_SETTINGS_COLLECTION_';

    /** @var string */
    protected string $entity = Setting::class;
}
