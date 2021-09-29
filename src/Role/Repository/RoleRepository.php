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
use Ares\Role\Entity\Role;

/**
 * Class RoleRepository
 *
 * @package Ares\Role\Repository
 */
class RoleRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_COLLECTION_';

    /** @var string */
    protected string $entity = Role::class;

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedRoles(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->orderBy('id', 'DESC');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param int $roleId
     *
     * @return Role|null
     */
    public function getRoleById(int $roleId) : Role {
        $searchCriteria = $this->getDataObjectManager()->where(['id' => $roleId])->addRelation('permission');

        return $this->getOneBy($searchCriteria);
    }

    /**
     *
     * @return Role|null
     */
    public function getRootRole() : Role
    {
        $searchCriteria = $this->getDataObjectManager()->where(['isRoot' => '1']);
        return $this->getOneBy($searchCriteria);
    }
}
