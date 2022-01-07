<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Role\Entity\Contract\RoleHierarchyInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleHierarchy;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\RoleHierarchyRepository;
use Ares\Role\Repository\RoleRepository;

/**
 * Class UpdateChildRoleParentService
 *
 * @package Ares\Role\Service
 */
class UpdateChildRoleParentService
{
    /**
     * UpdateChildRoleParentService constructor.
     *
     * @param RoleHierarchyRepository $roleHierarchyRepository
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        private RoleHierarchyRepository $roleHierarchyRepository,
        private RoleRepository $roleRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $parentRoleId */
        $parentRoleId = $data['parent_role_id'];

        /** @var int $childRoleId */
        $childRoleId = $data['child_role_id'];

        if($parentRoleId == $childRoleId) {
            throw new RoleException(
                __('Cant be same ID'),
                RoleResponseCodeInterface::RESPONSE_ROLE_IS_ROOT,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var Role $parentRole */
        $parentRole = $this->roleRepository->get($parentRoleId);

        /** @var Role $childRole */
        $childRole = $this->roleRepository->get($childRoleId);

        if($childRole->getIsRoot() == 1) {
            throw new RoleException(
                __('Child Role is Root'),
                RoleResponseCodeInterface::RESPONSE_ROLE_IS_ROOT,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var RoleHierarchy $existingRoleHierarchy */
        $existingRoleHierarchy = $this->roleHierarchyRepository->get($childRoleId, RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID);
        
        if($existingRoleHierarchy->getParentRoleId() == $parentRoleId) {
            /** @var RoleHierarchy $roleHierarchy */
            $roleHierarchy = $this->roleHierarchyRepository->save($existingRoleHierarchy);

            return response()->setData($roleHierarchy);
        }

        $parentIsGrandChild = $this->roleHierarchyRepository->roleIsGrandChild($parentRoleId);

        if ($parentIsGrandChild) {
            throw new RoleException(
                __('New Parent Role is a grand child'),
                RoleResponseCodeInterface::RESPONSE_ROLE_PARENT_IS_GRANDCHILD,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }
        
        if(!$parentRole->getIsRoot() == 1) {
            $hasChildren = $this->roleHierarchyRepository->hasChild($childRoleId);

            if ($hasChildren) {
                throw new RoleException(
                    __('Child Role has kids'),
                    RoleResponseCodeInterface::RESPONSE_ROLE_CHILD_HAS_KIDS,
                    HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
                );
            }
        }

        /** @var int $parentChildrenCount */
        $parentChildrenCount = count($this->roleHierarchyRepository->getChildIds([$parentRoleId]));

        /** @var RoleHierarchy $lastChild */
        $lastChild = $this->roleHierarchyRepository->getDataObjectManager()
                        ->where(RoleHierarchyInterface::COLUMN_PARENT_ROLE_ID, $parentRoleId)
                        ->orderBy(RoleHierarchyInterface::COLUMN_ORDER_ID, 'DESC')
                        ->first();

        $lastOrderId = ($lastChild) ? (($lastChild->getChildRoleId() === $childRoleId) ? $lastChild->getOrderId() : $lastChild->getOrderId() + 1) : 1;

        /** @var RoleHierarchy $editedRoleHierarchy */
        $editedRoleHierarchy = $this->getEditedChildRole($existingRoleHierarchy, $data, $lastOrderId);

        /** @var RoleHierarchy $editedRoleHierarchy */
        $editedRoleHierarchy = $this->roleHierarchyRepository->save($editedRoleHierarchy);

        return response()->setData($editedRoleHierarchy);
    }

    /**
     * @param int $parentRoleId
     * @param int $childRoleId
     *
     * @return RoleHierarchy
    */
    private function getEditedChildRole(RoleHierarchy $roleHierarchy, array $data, int $orderId): RoleHierarchy
    {
        return $roleHierarchy
            ->setParentRoleId($data['parent_role_id'])
            ->setOrderId($orderId);
    }
}