<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\Contract\PermissionInterface;
use Ares\Role\Entity\Permission;

/**
 * Class PermissionRepository
 *
 * @package Ares\Role\Repository
 */
class PermissionRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_PERMISSION_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_PERMISSION_COLLECTION_';

    /** @var string */
    protected string $entity = Permission::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedPermissionList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy(PermissionInterface::COLUMN_ID, 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
    *
    * @return Collection
    * @throws DataObjectManagerException
    */
    public function getPermissionList() {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy(PermissionInterface::COLUMN_ID);

        return $this->getList($searchCriteria);
    }

    /**
    * @param int $permissionId
    *
    * @return Permission
    * @throws DataObjectManagerException
    */
    public function getPermissionById(int $permissionId) {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                PermissionInterface::COLUMN_ID, 
                PermissionInterface::COLUMN_NAME, 
                PermissionInterface::COLUMN_DESCRIPTION
            ])
            ->where(PermissionInterface::COLUMN_ID, $permissionId);

        return $this->getOneBy($searchCriteria);
    }

    /**
    * @param string $permissionName
    *
    * @return Permission
    * @throws DataObjectManagerException
    */
    public function getPermissionByName(string $permissionName) {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                PermissionInterface::COLUMN_ID, 
                PermissionInterface::COLUMN_NAME, 
                PermissionInterface::COLUMN_DESCRIPTION
            ])
            ->where(PermissionInterface::COLUMN_NAME, $permissionName);

        return $this->getOneBy($searchCriteria, true, false);
    }
}
