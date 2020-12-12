<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Setting\Repository;

use Ares\Framework\Repository\BaseRepository;
use Ares\Setting\Entity\Setting;

/**
 * Class SettingsRepository
 *
 * @package Ares\Setting\Repository
 */
class SettingRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_SETTING_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_SETTING_COLLECTION_';

    /** @var string */
    protected string $entity = Setting::class;
}
