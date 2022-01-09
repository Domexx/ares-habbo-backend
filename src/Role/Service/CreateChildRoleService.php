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
 * Class CreateChildRoleService
 *
 * @package Ares\Role\Service
 */
class CreateChildRoleService
{
    /**
     * CreateChildRoleService constructor.
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

        $isCycle = $this->checkForCycle($parentRoleId, $childRoleId);

        if ($isCycle) {
            throw new RoleException(
                __('Cycle detected for given Role notations'),
                RoleResponseCodeInterface::RESPONSE_ROLE_CYCLE_DETECTED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var RoleHierarchy $lastChild */
        $lastChild = $this->roleHierarchyRepository->getDataObjectManager()
                        ->where(RoleHierarchyInterface::COLUMN_PARENT_ROLE_ID, $parentRoleId)
                        ->orderBy(RoleHierarchyInterface::COLUMN_ORDER_ID, 'DESC')
                        ->first();

        /** @var int $lastOrderId */
        $lastOrderId = 1;

        if($lastChild) {
            if($lastChild->getChildRoleId() === $childRoleId) {
                $lastOrderId = $lastChild->getOrderId();
            } else {
                $lastOrderId = $lastChild->getOrderId() + 1;
            }
        }

        $newChildRole = $this->getNewChildRole($data, $lastOrderId);

        /** @var RoleHierarchy $newChildRole */
        $newChildRole = $this->roleHierarchyRepository->save($newChildRole);

        return response()
            ->setData($newChildRole);
    }

    /**
     * @param int $parentRoleId
     * @param int $childRoleId
     *
     * @return RoleHierarchy
     */
    private function getNewChildRole(array $data, int $orderId): RoleHierarchy
    {
        $roleHierarchy = new RoleHierarchy();

        $roleHierarchy
            ->setParentRoleId($data['parent_role_id'])
            ->setChildRoleId($data['child_role_id'])
            ->setOrderId($orderId)
            ->setCreatedAt(new \DateTime());

        return $roleHierarchy;
    }

    /**
     * @param int $parentRoleId
     * @param int $childRoleId
     *
     * @return bool
     * @throws QueryException
     */
    private function checkForCycle(int $parentRoleId, int $childRoleId): bool
    {
        $hasChildRole = $this->roleHierarchyRepository->hasChildRoleId($parentRoleId, $childRoleId);
        $hasParentRole = $this->roleHierarchyRepository->hasParentRoleId($parentRoleId, $childRoleId);
        $areBrothers = $this->roleHierarchyRepository->areBrothers($parentRoleId, $childRoleId);
        $parentIsGrandChild = $this->roleHierarchyRepository->roleIsGrandChild($parentRoleId);

        return $hasChildRole || $hasParentRole || $areBrothers || $parentIsGrandChild;
    }
}
