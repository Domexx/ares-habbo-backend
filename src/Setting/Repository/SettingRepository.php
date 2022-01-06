<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Setting\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Model\Query\Collection;
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

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedSettingList(int $page, int $resultPerPage): PaginatedCollection
    {
        return $this->getPaginatedList(
            $this->getDataObjectManager(),
            $page,
            $resultPerPage
        );
    }

    /**
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getSettingList(): Collection
    {
        return $this->getList($this->getDataObjectManager());
    }
}
