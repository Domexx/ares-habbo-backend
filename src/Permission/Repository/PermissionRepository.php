<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Permission\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Permission\Entity\Permission;
use Ares\Framework\Model\Query\Collection;

/**
 * Class PermissionRepository
 *
 * @package Ares\Permission\Repository
 */
class PermissionRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_PERMISSION_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = Permission::class;

    /**
     * @return Collection
     * @throws DataObjectManagerException
     */
    public function getListOfUserWithRanks(): Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where('id', '>', 3)
            ->orderBy('id', 'DESC')
            ->addRelation('users');

        return $this->getList($searchCriteria);
    }
}
