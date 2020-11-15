<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Vote\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Model\DataObject;
use Ares\Vote\Entity\Vote;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Repository\VoteRepository;

/**
 * Class CreateVoteService
 *
 * @package Ares\Vote\Service
 */
class CreateVoteService
{
    /**
     * CreateVoteService constructor.
     *
     * @param VoteRepository $voteRepository
     * @param GetVoteEntityService $getVoteEntityService
     */
    public function __construct(
        private VoteRepository $voteRepository,
        private GetVoteEntityService $getVoteEntityService
    ) {}

    /**
     * Create new vote with given data.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws VoteException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $vote = $this->getNewVote($userId, $data);

        $existingVote = $this->voteRepository->getExistingVote($vote, $userId);

        if ($existingVote) {
            throw new VoteException(__('User already voted for this entity'), 422);
        }

        $entityRepository = $this->getVoteEntityService->execute($vote->getVoteEntity());

        if (!$entityRepository) {
            throw new VoteException(__('Related EntityRepository could not be found'));
        }

        /** @var DataObject $entity */
        $entity = $entityRepository->get($vote->getEntityId());

        $vote = $this->voteRepository->save($vote);

        return response()
            ->setData($vote);
    }

    /**
     * Return new vote.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return Vote
     */
    private function getNewVote(int $userId, array $data): Vote
    {
        $vote = new Vote();

        return $vote
            ->setEntityId($data['entity_id'])
            ->setVoteEntity($data['vote_entity'])
            ->setVoteType($data['vote_type'])
            ->setUserId($userId);
    }
}
