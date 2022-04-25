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
use Ares\Role\Entity\Contract\RoleInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\RoleRepository;

/**
 * Class EditRoleService
 *
 * @package Ares\Role\Service
 */
class EditRoleService
{
    /**
     * EditRoleService constructor.
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
        $roleId = $data['id'];

        /** @var Role $role */
        $role = $this->roleRepository->get($roleId, RoleInterface::COLUMN_ID, false, false);

        /** @var Role $existingRole */
        $existingRole = $this->roleRepository->get($data['name'], RoleInterface::COLUMN_NAME, true);

        if ($existingRole && $existingRole->getId() !== $role->getId()) {
            throw new RoleException(
                __('Role %s already exists',
                    [$existingRole->getName()]),
                RoleResponseCodeInterface::RESPONSE_ROLE_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $role = $this->getEditedRole($role, $data);

        /** @var Role $role */
        $role = $this->roleRepository->save($role);

        return response()->setData($role);
    }

    /**
     * @param array $data
     *
     * @return Role
    */
    private function getEditedRole(Role $role, array $data): Role
    {
        return $role
            ->setName($data['name'] ?: $role->getName())
            ->setDescription($data['description'] ?: $role->getDescription())
            ->setStatus($data['status'])
            ->setUpdatedAt(new \DateTime());
    }
}
