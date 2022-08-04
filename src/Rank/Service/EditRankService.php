<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rank\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Rank\Entity\Contract\RankInterface;
use Ares\Rank\Entity\Rank;
use Ares\Rank\Exception\RankException;
use Ares\Rank\Interfaces\Response\RankResponseCodeInterface;
use Ares\Rank\Repository\RankRepository;

/**
 * Class CreateRankService
 *
 * @package Ares\Rank\Service
 */
class EditRankService
{
    /**
     * CreateRankService constructor.
     *
     * @param RankRepository $rankRepository
     */
    public function __construct(
        private RankRepository $rankRepository
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

        if($this->rankRepository->getDataObjectManager()
            ->where([RankInterface::COLUMN_ID => $rankId])
            ->update($data)
        ) {
            /** @var Rank $rank */
            $rank = $this->rankRepository->get($rankId, RankInterface::COLUMN_ID, false, false);
        } else {
            throw new RankException(
                __('Rank failed editing ' . $rankId),
                RankResponseCodeInterface::RESPONSE_RANK_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        /** @var Rank $rank */
        $rank = $this->rankRepository->save($rank);

        return response()->setData($rank);
    }
}