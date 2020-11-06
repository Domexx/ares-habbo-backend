<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
     * @var VoteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * DeleteVoteService constructor.
     *
     * @param VoteRepository $voteRepository
     */
    public function __construct(
        VoteRepository $voteRepository
    ) {
        $this->voteRepository = $voteRepository;
    }

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
            (int) $data['entity_id'],
            (int) $data['vote_entity'],
            (int) $data['vote_type'],
            $userId
        );

        if (!$vote) {
            throw new VoteException(__('Vote could not be found by given data.'), 404);
        }

        $deleted = $this->voteRepository->delete($vote->getId());

        return response()
            ->setData($deleted);
    }
}
