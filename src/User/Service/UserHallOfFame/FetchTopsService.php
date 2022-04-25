<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\User\Service\UserHallOfFame;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Role\Repository\PermissionRepository;
use Ares\Role\Repository\RolePermissionRepository;
use Ares\User\Entity\User;
use Ares\Role\Entity\Permission;
use Ares\Role\Entity\RolePermission;
use Ares\Role\Entity\RoleRank;
use Ares\Role\Repository\RoleRankRepository;
use Ares\User\Repository\UserCurrencyRepository;
use Ares\User\Repository\UserRepository;

/**
 * Class FetchTopsService
 *
 * @package Ares\User\Service\UserHallOfFame
 */
class FetchTopsService
{
    /**
     * FetchTopsService constructor.
     *
     * @param UserCurrencyRepository $userCurrencyRepository
     * @param UserRepository           $userRepository
     */
    public function __construct(
        private UserCurrencyRepository $userCurrencyRepository,
        private RolePermissionRepository $rolePermissionRepository,
        private RoleRankRepository $roleRankRepository,
        private PermissionRepository $permissionRepository,
        private UserRepository $userRepository
    ) {}

    /**
     * @return CustomResponseInterface
     *
     * @throws DataObjectManagerException|NoSuchEntityException
     */
    public function execute($type) : CustomResponseInterface
    {
        /** @var Permission $permission */
        $permission = $this->permissionRepository->getPermissionByName('hide-leaderboard');

        /** @var RolePermission[] $rolePermissions */
        $rolePermissions = $this->rolePermissionRepository->getRolesWithPermissionId($permission->getId());

        /** @var array $rankIds */
        $rankIds = [];

        if($rolePermissions) {
            foreach ($rolePermissions as $rolePermission) {
                /** @var RoleRank $roleRank */
                $roleRank = $this->roleRankRepository->getRoleRankByRoleId($rolePermission->getRoleId());
    
                if($roleRank) {
                    array_push($rankIds, $roleRank->getRankId());
                }
            }
        }

        switch($type) {
            case -1:
                $tops = $this->userRepository->getTopCredits($rankIds);
                break;
            default:
                $tops = $this->userCurrencyRepository->getTops($type, $rankIds);
        }

        return response()
            ->setData($tops);
    }
}