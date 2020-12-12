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
use Ares\Role\Entity\Role;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\RoleRepository;

/**
 * Class CreateRoleService
 *
 * @package Ares\Role\Service
 */
class CreateRoleService
{
    /**
     * CreateRoleService constructor.
     *
     * @param RoleRepository $roleRepository
     */
    public function __construct(
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
        /** @var Role $existingRole */
        $existingRole = $this->roleRepository->get($data['name'], 'name', true);

        if ($existingRole) {
            throw new RoleException(
                __('Role %s already exists',
                    [$existingRole->getName()]),
                RoleResponseCodeInterface::RESPONSE_ROLE_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $role = $this->getNewRole($data);

        /** @var Role $role */
        $role = $this->roleRepository->save($role);

        return response()
            ->setData($role);
    }

    /**
     * @param array $data
     *
     * @return Role
     */
    private function getNewRole(array $data): Role
    {
        $role = new Role();

        $role
            ->setName($data['name'])
            ->setDescription($data['description'])
            ->setCreatedAt(new \DateTime());

        return $role;
    }
}
