<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
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

    public function getListOfPermissions() : Collection {
        $searchCriteria = $this->getDataObjectManager()
            ->addRelation('role');

        return $this->getList($searchCriteria, false);
    }

    public function getPermissionById(int $permissionId, bool $appendUsers = false) {
        $searchCriteria = $this->getDataObjectManager()
            ->select(['permissions.*'])
            ->where('id', $permissionId)
            ->addRelation('role');

        if($appendUsers) {
            $searchCriteria = $searchCriteria->addRelation('users');
        }

        return $this->getOneBy($searchCriteria, true, false);
    }

    public function getListOfColumns() : array {
        //TODO Edit Ares Core - DataObjectManagerFactory make Manager be able to access to SchemaBuilder to retrieve list of columns safely.
        $searchCriteria = $this->getDataObjectManager()
            ->getConnection()
            ->select('SHOW COLUMNS FROM `permissions`');
            
        return $searchCriteria;
    }
}
