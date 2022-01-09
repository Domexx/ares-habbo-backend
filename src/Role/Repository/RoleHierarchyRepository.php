<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Repository;

use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Repository\BaseRepository;
use Ares\Role\Entity\Contract\RoleHierarchyInterface;
use Ares\Role\Entity\RoleHierarchy;
use Illuminate\Database\QueryException;

/**
 * Class RoleHierarchyRepository
 *
 * @package Ares\Role\Repository
 */
class RoleHierarchyRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ROLE_HIERARCHY_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ROLE_HIERARCHY_COLLECTION_';

    /** @var string */
    protected string $entity = RoleHierarchy::class;

    /**
     * //BUG getList doesn't work with cache correctly
     * 
     * @param array $parentIds
     *
     * @return array|null
     */
    public function getChildIds(array $parentIds): ?array
    {
        $searchCriteria = $this->getDataObjectManager()
                            ->whereIn('parent_role_id', $parentIds)
                            ->orderBy(RoleHierarchyInterface::COLUMN_ORDER_ID);

        return $this->getList($searchCriteria)->get('child_role_id');
    }

    /**
     * @param array $childIds
     *
     * @return array|null
     */
    private function getParentIds(array $childIds): ?array
    {
        $searchCriteria = $this->getDataObjectManager()
            ->whereIn('child_role_id', $childIds);

        if($searchCriteria->count() == 0) {
            return [];
        }

        return $this->getList($searchCriteria)->get('parent_role_id');
    }

    /**
     * @param array $parentIds
     *
     * @return array
     * @throws QueryException
     */
    private function getAllChildRoleIds(array $parentIds): array
    {
        $allChildIds = [];

        while (count($parentIds) > 0) {
            $parentIds = $this->getChildIds($parentIds);

            if (!$parentIds) {
                break;
            }

            $allChildIds = array_merge($allChildIds, $parentIds);
        }

        return $allChildIds;
    }

    /**
     * @param int $parentRoleId
     * @param int $findingChildId
     *
     * @return bool
     * @throws QueryException
     */
    public function hasChildRoleId(int $parentRoleId, int $findingChildId): bool
    {
        $parentChildren = $this->getChildIds([$parentRoleId]);

        if ($parentChildren && count($parentChildren) > 0) {

            if (in_array($findingChildId, $parentChildren, true)) {
                return true;
            }

            foreach ($parentChildren as $child) {
                if ($this->hasChildRoleId($child, $findingChildId)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param int $parentRoleId
     * @param int $findingChildId
     *
     * @return bool
     * @throws QueryException
     */
    public function hasChild(int $roleId): bool
    {
        $childIds = $this->getChildIds([$roleId]);

        return $childIds && count($childIds) > 0;
    }

     /**
     * @param int $parentRoleId
     * @param int $findingParentId
     *
     * @return bool
     * @throws QueryException
     */
    public function hasParentRoleId(int $parentRoleId, int $findingParentId): bool
    {
        $parentIds = $this->getParentIds([$parentRoleId]);

        if(count($parentIds) > 0) {
            if(in_array($findingParentId, $parentIds, true)) {
                return true;
            }

            foreach($parentIds as $parentId) {
                if($this->hasParentRoleId($parentId, $findingParentId)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
    * @param int $parentRoleId
    * @param int $childRoleId
    *
    * @return bool
    * @throws QueryException
    */
    public function areBrothers(int $parentRoleId, int $childRoleId) : bool {
        $parentParentIds = $this->getParentIds([$parentRoleId]);
        $childParentIds = $this->getParentIds([$childRoleId]);

        if(count($parentParentIds) > 0 && count($childParentIds) > 0) {
            if($parentParentIds === $childParentIds) {
                return true;
            }

            foreach($childParentIds as $parentId) {
                if($this->areBrothers($parentId, $parentRoleId)) {
                    return true;
                }
            }
        }
        
        return false;
    }

    /**
    * @param int $parentRoleId
    * @param int $parentsCount
    *
    * @return bool
    * @throws QueryException
    */
    public function roleIsGrandChild(int $parentRoleId, $parentsCount = 0) : bool {
        $parentParentIds = $this->getParentIds([$parentRoleId]);
        
        if(count($parentParentIds) > 0) {
            $parentsCount++;

            if($parentsCount == 2) {
                return true;
            }

            foreach($parentParentIds as $parentId) {
                if($this->roleIsGrandChild($parentId, $parentsCount)) {
                    return true;
                }
            } 
        }

        return false;
    }

    /**
     * @param array $rootRoleIds
     *
     * @return mixed
     * @throws QueryException
     */
    public function getAllRoleIdsHierarchy(array $rootRoleIds): mixed
    {
        $childRoleIds = $this->getAllChildRoleIds($rootRoleIds);

        return (count($childRoleIds) > 0) ? array_merge($rootRoleIds, $childRoleIds) : $rootRoleIds;
    }
}
