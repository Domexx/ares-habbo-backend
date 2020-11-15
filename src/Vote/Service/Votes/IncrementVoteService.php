<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Vote\Service\Votes;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Model\DataObject;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Interfaces\VoteTypeInterface;
use Ares\Vote\Service\GetVoteEntityService;
use Exception;

/**
 * Class IncrementVoteService
 *
 * @package Ares\Vote\Service\Votes
 */
class IncrementVoteService
{
    /**
     * @var GetVoteEntityService
     */
    private GetVoteEntityService $getVoteEntityService;

    /**
     * DecrementVoteService constructor.
     *
     * @param GetVoteEntityService $getVoteEntityService
     */
    public function __construct(
        GetVoteEntityService $getVoteEntityService
    ) {
        $this->getVoteEntityService = $getVoteEntityService;
    }

    /**
     * Increments votes by given data.
     *
     * @param int $entityId
     * @param int $voteEntity
     * @param int $voteType
     *
     * @return bool
     * @throws VoteException
     * @throws NoSuchEntityException
     */
    public function execute(int $entityId, int $voteEntity, int $voteType): bool
    {
        $entityRepository = $this->getVoteEntityService->execute($voteEntity);

        if (!$entityRepository) {
            throw new VoteException(__('Related EntityRepository could not be found'));
        }

        /** @var DataObject $entity */
        $entity = $entityRepository->get($entityId);

        if ($voteType === VoteTypeInterface::VOTE_LIKE) {
            $likes = $entity->getLikes();
            $entity->setLikes(++$likes);
        } else {
            $dislikes = $entity->getDislikes();
            $entity->setDislikes(++$dislikes);
        }

        try {
            $entityRepository->save($entity);
            return true;
        } catch (Exception) {
            return false;
        }
    }

}
