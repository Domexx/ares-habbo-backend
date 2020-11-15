<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Vote\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Vote\Entity\Vote;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Repository\VoteRepository;

/**
 * Class DeleteVoteService
 *
 * @package Ares\Vote\Service
 */
class DeleteVoteService
{
    /**
     * DeleteVoteService constructor.
     *
     * @param VoteRepository $voteRepository
     */
    public function __construct(
        private VoteRepository $voteRepository
    ) {}

    /**
     * Deletes vote by given data.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws VoteException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        /** @var Vote $vote */
        $vote = $this->voteRepository->getVoteForDeletion(
            $data['entity_id'],
            $data['vote_entity'],
            $data['vote_type'],
            $userId
        );

        if (!$vote) {
            throw new VoteException(__('No Vote was found'), 404);
        }

        $deleted = $this->voteRepository->delete($vote->getId());

        return response()
            ->setData($deleted);
    }
}
