<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Role\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Entity\Role;
use Ares\Role\Entity\RoleUser;
use Ares\Role\Exception\RoleException;
use Ares\Role\Repository\RoleRepository;
use Ares\Role\Repository\RoleUserRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;

/**
 * Class AssignUserToRoleService
 *
 * @package Ares\Role\Service
 */
class AssignUserToRoleService
{
    /**
     * @var RoleUserRepository
     */
    private RoleUserRepository $roleUserRepository;

    /**
     * @var RoleRepository
     */
    private RoleRepository $roleRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * AssignUserToRoleService constructor.
     *
     * @param RoleUserRepository $roleUserRepository
     * @param RoleRepository     $roleRepository
     * @param UserRepository     $userRepository
     */
    public function __construct(
        RoleUserRepository $roleUserRepository,
        RoleRepository $roleRepository,
        UserRepository $userRepository
    ) {
        $this->roleUserRepository = $roleUserRepository;
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
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

        if (!$role || !$user) {
            throw new RoleException(__('Could not find called Role or User'));
        }

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
            ->setRoleId($roleId);

        return $roleUser;
    }
}
