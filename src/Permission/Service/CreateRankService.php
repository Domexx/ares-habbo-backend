<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Permission\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Permission\Entity\Permission;
use Ares\Permission\Exception\RankException;
use Ares\Permission\Interfaces\Response\RankResponseCodeInterface;
use Ares\Permission\Repository\PermissionRepository;

/**
 * Class CreateRankService
 *
 * @package Ares\Permission\Service
 */
class CreateRankService
{
    /**
     * CreateRankService constructor.
     *
     * @param PermissionRepository $permissionRepository
     */
    public function __construct(
        private PermissionRepository $permissionRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws RankException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var Permission $existingRank */
        $existingRank = $this->permissionRepository->get($data['rank_name'], 'rank_name', true, false);

        if ($existingRank) {
            throw new RankException(
                __('Rank %s already exists',
                    [$existingRank->getRankName()]),
                RankResponseCodeInterface::RESPONSE_RANK_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        if($this->permissionRepository->getDataObjectManager()->insert($data)) {            
            
            /** @var Permission $rank */
            $rank = $this->permissionRepository->get($data['rank_name'], 'rank_name', true, false);

            return response()->setData($rank);
        } else {
            throw new RankException(
                __('Rank failed creating'),
                RankResponseCodeInterface::RESPONSE_RANK_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }
    }
}