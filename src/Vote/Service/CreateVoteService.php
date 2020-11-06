<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Service;

use Ares\Framework\Exception\DataObjectManagerException;
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
     * @var VoteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * @var GetVoteEntityService
     */
    private GetVoteEntityService $getVoteEntityService;

    /**
     * CreateVoteService constructor.
     *
     * @param VoteRepository $voteRepository
     * @param GetVoteEntityService $getVoteEntityService
     */
    public function __construct(
        VoteRepository $voteRepository,
        GetVoteEntityService $getVoteEntityService
    ) {
        $this->voteRepository = $voteRepository;
        $this->getVoteEntityService = $getVoteEntityService;
    }

    /**
     * Create new vote with given data.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws VoteException
     * @throws DataObjectManagerException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $vote = $this->getNewVote($userId, $data);

        $existingVote = $this->voteRepository->getExistingVote($vote, $userId);

        if ($existingVote) {
            throw new VoteException(__('User already voted for this entity.'), 422);
        }

        $entityRepository = $this->getVoteEntityService->execute($vote->getVoteEntity());

        if (!$entityRepository) {
            throw new VoteException(__('Related EntityRepository could not be found'));
        }

        /** @var DataObject $entity */
        $entity = $entityRepository->get($vote->getEntityId());

        if (!$entity) {
            throw new VoteException(__('The related vote entity has no existing data.'), 404);
        }

        $vote = $this->voteRepository->save($entity);

        return response()
            ->setData($vote);
    }

    /**
     * Return new vote.
     *
     * @param int   $user_id
     * @param array $data
     *
     * @return Vote
     */
    private function getNewVote(int $user_id, array $data): Vote
    {
        $vote = new Vote();

        return $vote
            ->setEntityId($data['entity_id'])
            ->setVoteEntity($data['vote_entity'])
            ->setVoteType($data['vote_type'])
            ->setUserId($user_id);
    }
}
