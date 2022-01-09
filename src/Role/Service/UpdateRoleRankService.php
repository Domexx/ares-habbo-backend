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
use Ares\Role\Entity\RoleRank;
use Ares\Role\Exception\RoleException;
use Ares\Role\Interfaces\Response\RoleResponseCodeInterface;
use Ares\Role\Repository\RoleRankRepository;
use Ares\Role\Repository\RoleRepository;

/**
 * Class UpdateRoleRankService
 *
 * @package Ares\Role\Service
 */
class UpdateRoleRankService
{
    /**
     * UpdateRoleRankService constructor.
     *
     * @param RoleRankRepository $roleRankRepository
     * @param RoleRepository          $roleRepository
     */
    public function __construct(
        private RoleRankRepository $roleRankRepository,
        private RoleRepository $roleRepository
    ) {}

    /**
     *
     * 
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RoleException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $rankId */
        $rankId = $data['rank_id'];

        /** @var int $roleId */
        $roleId = $data['role_id'];
        
        $searchCriteria = $this->roleRankRepository
                            ->getDataObjectManager()
                            ->select([
                                RoleRankInterface::COLUMN_ID,
                                RoleRankInterface::COLUMN_RANK_ID,
                                RoleRankInterface::COLUMN_ROLE_ID,
                                RoleRankInterface::COLUMN_CREATED_AT
                            ])
                            ->where(RoleRankInterface::COLUMN_ROLE_ID, $roleId);

        /** @var RoleRank $existingRoleRank */
        $existingRoleRank = $this->roleRankRepository->getOneBy($searchCriteria);

        $searchCriteria = $this->roleRankRepository
                            ->getDataObjectManager()
                            ->select([
                                RoleRankInterface::COLUMN_ID,
                                RoleRankInterface::COLUMN_RANK_ID,
                                RoleRankInterface::COLUMN_ROLE_ID,
                                RoleRankInterface::COLUMN_CREATED_AT
                            ])
                            ->where(RoleRankInterface::COLUMN_RANK_ID, $rankId);

        /** @var RoleRank $existingRankRole */
        $existingRankRole = $this->roleRankRepository->getOneBy($searchCriteria, true);
        
        if($existingRankRole) {
            throw new RoleException(
                __('There is already a Role assigned to that Rank'),
                RoleResponseCodeInterface::RESPONSE_ROLE_ALREADY_ASSIGNED_TO_RANK,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $roleRank = $this->getEditedRoleRank($existingRoleRank, $data);

        /** @var RoleRank $roleRank */
        $roleRank = $this->roleRankRepository->save($roleRank);

        /** @var Role $role */
        $role = $this->roleRepository->get($roleId);

        $role->setData('permissions', null);

        return response()->setData($roleRank);
    }

    /**
     * @param array $data
     *
     * @return RoleRank
     */
    private function getEditedRoleRank(RoleRank $roleRank, array $data): RoleRank
    {
        return $roleRank
            ->setRankId($data['rank_id'] ?: $roleRank->getRankId())
            ->setRoleId($data['role_id'] ?: $roleRank->getRoleId());
    }
}