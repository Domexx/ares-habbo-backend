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
use Ares\Rank\Entity\Rank;
use Ares\Rank\Exception\RankException;
use Ares\Rank\Interfaces\Response\RankResponseCodeInterface;
use Ares\Rank\Repository\RankRepository;

/**
 * Class CreateRankService
 *
 * @package Ares\Rank\Service
 */
class CreateRankService
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
        /** @var Rank $existingRank */
        $existingRank = $this->rankRepository->get($data['rank_name'], 'rank_name', true, false);

        if ($existingRank) {
            throw new RankException(
                __('Rank %s already exists',
                    [$existingRank->getRankName()]),
                RankResponseCodeInterface::RESPONSE_RANK_ALREADY_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        if($this->rankRepository->getDataObjectManager()->insert($data)) {            
            
            /** @var Rank $rank */
            $rank = $this->rankRepository->get($data['rank_name'], 'rank_name', true, false);

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