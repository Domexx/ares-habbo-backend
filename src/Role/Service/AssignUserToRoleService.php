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
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleUser;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Repository\RoleUserRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use DateTime;

/**
 * Class AssignUserToRoleService
 *
 * @package Ares\Role\Service
 */
class AssignUserToRoleService
{
    /**
     * AssignUserToRoleService constructor.
     *
     * @param RoleUserRepository $roleUserRepository
     * @param RoleRepository     $roleRepository
     * @param UserRepository     $userRepository
     */
    public function __construct(
        private RoleUserRepository $roleUserRepository,
        private RoleRepository $roleRepository,
        private UserRepository $userRepository
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
        /** @var int $userId */
        $userId = $data['role_id'];

        /** @var int $roleId */
        $roleId = $data['role_id'];

        /** @var Role $role */
        $role = $this->roleRepository->get($userId);

        /** @var User $user */
        $user = $this->userRepository->get($roleId);

        /** @var RoleUser $isRoleAlreadyAssigned */
        $isRoleAlreadyAssigned = $this->roleUserRepository
            ->getUserAssignedRole(
                $role->getId(),
                $user->getId()
            );

        if ($isRoleAlreadyAssigned) {
            throw new RoleException(__('There is already a Role assigned to that User'));
        }

        $roleUser = $this->getNewRoleUser($role->getId(), $user->getId());

        $this->roleUserRepository->save($roleUser);

        return response()
            ->setData(true);
    }

    /**
     * @param int $roleId
     * @param int $userId
     *
     * @return RoleUser
     */
    private function getNewRoleUser(int $roleId, int $userId): RoleUser
    {
        $roleUser = new RoleUser();

        $roleUser
            ->setUserId($userId)
            ->setRoleId($roleId)
            ->setCreatedAt(new DateTime());

        return $roleUser;
    }
}
