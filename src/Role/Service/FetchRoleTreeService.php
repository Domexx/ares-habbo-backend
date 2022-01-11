<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Role\Service;

use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Repository\RoleHierarchyRepository;

/**
 * Class FetchRoleTreeService
 *
 * @package Ares\Role\Service
 */
class FetchRoleTreeService {

    /**
     * FetchRoleTreeService constructor.
     *
     * @param RoleRepository        $roleRepository
     * @param RoleHierarchyRepository  $roleHierarchyRepository
    */
    public function __construct(
        private RoleRepository $roleRepository,
        private RoleHierarchyRepository $roleHierarchyRepository
    ) {}

    /**
     * //TODO Maybe a re-work on getChildIds
     * 
     * @param Role $rootRole
     *
     * @return CustomResponseInterface
     * @throws NoSuchEntityException
    */
    public function execute(Role $rootRole) : CustomResponseInterface {

        $rootChildren = $this->roleHierarchyRepository->getChildIds([$rootRole->getId()]);

        $rootRole->children = [];

        if($rootChildren && count($rootChildren) > 0) {
            foreach($rootChildren as $categoryId) {

                /** @var Role $categoryRole */
                $categoryRole = $this->roleRepository->get($categoryId);

                $categoryChildren = $this->roleHierarchyRepository->getChildIds([$categoryId]);

                $categoryRole->children = [];
                
                if($categoryChildren && count($categoryChildren) > 0) {

                    foreach($categoryChildren as $roleId) {
                        /** @var Role $role */
                        $role = $this->roleRepository->get($roleId);

                        $role->getPermission(true, false);

                        array_push($categoryRole->children, $role);
                    }
                }

                array_push($rootRole->children, $categoryRole);
            }
        }

        return response()->setData($rootRole);
    }
}