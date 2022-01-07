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
 * Class UpdateChildRoleOrderService
 *
 * @package Ares\Role\Service
 */
class UpdateChildRoleOrderService
{
    /**
     * UpdateChildRoleOrderService constructor.
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
        /** @var int $childRoleId */
        $childRoleId = $data['child_role_id'];

        $orderId = $data['order_id'];

        /** @var RoleHierarchy $existingRoleHierarchy */
        $existingRoleHierarchy = $this->roleHierarchyRepository->get($childRoleId, RoleHierarchyInterface::COLUMN_CHILD_ROLE_ID);

        $existingRoleHierarchy->setOrderId($orderId);

        /** @var RoleHierarchy $existingRoleHierarchy */
        $existingRoleHierarchy = $this->roleHierarchyRepository->save($existingRoleHierarchy);

        return response()->setData($existingRoleHierarchy);
    }
}