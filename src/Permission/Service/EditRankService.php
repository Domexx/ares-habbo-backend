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
use Ares\Permission\Entity\Contract\PermissionInterface;
use Ares\Permission\Entity\Permission;
use Ares\Permission\Exception\RankException;
use Ares\Permission\Interfaces\Response\RankResponseCodeInterface;
use Ares\Permission\Repository\PermissionRepository;

/**
 * Class CreateRankService
 *
 * @package Ares\Permission\Service
 */
class EditRankService
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
        /** @var int $rankId */
        $rankId = $data['id'];

        if($this->permissionRepository->getDataObjectManager()
            ->where([PermissionInterface::COLUMN_ID => $rankId])
            ->update($data)
        ) {
            /** @var Permission $rank */
            $rank = $this->permissionRepository->get($rankId, PermissionInterface::COLUMN_ID, false, false);
        } else {
            throw new RankException(
                __('Rank failed editing ' . $rankId),
                RankResponseCodeInterface::RESPONSE_RANK_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var Permission $rank */
        $rank = $this->permissionRepository->save($rank);

        return response()->setData($rank);
    }
}