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
use Ares\Role\Entity\Contract\RoleRankInterface;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\RoleRankRepository;
use Ares\Role\Repository\RoleRepository;

/**
 * Class DeleteRoleRankService
 *
 * @package Ares\Role\Service
 */
class DeleteRoleRankService
{
    /**
     * DeleteRoleRankService constructor.
     *
     * @param RoleRankRepository $roleRankRepository
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        private RoleRankRepository $roleRankRepository,
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
    public function execute(int $roleId): CustomResponseInterface
    {
        $deleted = $this->roleRankRepository
            ->getDataObjectManager()
            ->where(RoleRankInterface::COLUMN_ROLE_ID, $roleId)
            ->delete();

        if (!$deleted) {
            throw new RoleException(
                __('Role Rank could not be deleted'),
                RoleResponseCodeInterface::RESPONSE_ROLE_RANK_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return response()
            ->setData(true);
    }
}
