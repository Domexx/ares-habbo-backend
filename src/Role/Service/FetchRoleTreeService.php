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

        if(count($rootChildren) > 0) {
            foreach($rootChildren as $category) {

                /** @var Role $groupRole */
                $groupRole = $this->roleRepository->get($category);

                $groupChildren = $this->roleHierarchyRepository->getChildIds([$category]);

                $groupRole->children = [];

                if(count($groupChildren) > 0) {
                    foreach($groupChildren as $groupChild) {

                        /** @var Role $roleRank */
                        $roleRank = $this->roleRepository->get($groupChild);
                        
                        $roleRank->getPermissionWithUsers();

                        array_push($groupRole->children, $roleRank);
                    }
                }

                array_push($rootRole->children, $groupRole);
            }
        }

        return response()->setData($rootRole);
    }
}