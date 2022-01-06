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
 * Class DeleteChildRoleService
 *
 * @package Ares\Role\Service
 */
class DeleteChildRoleService
{
    /**
     * DeleteChildRoleService constructor.
     *
     * @param RoleHierarchyRepository $roleHierarchyRepository
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        private RoleHierarchyRepository $roleHierarchyRepository,
        private RoleRepository $roleRepository
    ) {}

    /**
     * @param int $childRoleId
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws NoSuchEntityException
     */
    public function execute(int $childRoleId): CustomResponseInterface
    {
        $deleted = $this->roleHierarchyRepository->delete($childRoleId);

        if (!$deleted) {
            throw new RoleException(
                __('Child Role could not be deleted'),
                RoleResponseCodeInterface::RESPONSE_CHILD_ROLE_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
