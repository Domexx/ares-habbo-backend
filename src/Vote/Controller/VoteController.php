<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Repository\VoteRepository;
use Ares\Vote\Service\CreateVoteService;
use Ares\Vote\Service\DeleteVoteService;
use Ares\Vote\Service\Votes\DecrementVoteService;
use Ares\Vote\Service\Votes\IncrementVoteService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class VoteController
 *
 * @package Ares\Vote\Controller
 */
class VoteController extends BaseController
{
    /**
     * @var VoteRepository
     */
    private VoteRepository $voteRepository;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var CreateVoteService
     */
    private CreateVoteService $createVoteService;

    /**
     * @var DeleteVoteService
     */
    private DeleteVoteService $deleteVoteService;

    /**
     * @var IncrementVoteService
     */
    private IncrementVoteService $incrementVoteService;

    /**
     * @var DecrementVoteService
     */
    private DecrementVoteService $decrementVoteService;

    /**
     * VoteController constructor.
     *
     * @param   VoteRepository          $voteRepository
     * @param   ValidationService       $validationService
     * @param   CreateVoteService       $createVoteService
     * @param   DeleteVoteService       $deleteVoteService
     * @param   IncrementVoteService    $incrementVoteService
     * @param   DecrementVoteService    $decrementVoteService
     */
    public function __construct(
        VoteRepository $voteRepository,
        ValidationService $validationService,
        CreateVoteService $createVoteService,
        DeleteVoteService $deleteVoteService,
        IncrementVoteService $incrementVoteService,
        DecrementVoteService $decrementVoteService
    ) {
        $this->voteRepository         = $voteRepository;
        $this->validationService      = $validationService;
        $this->createVoteService      = $createVoteService;
        $this->deleteVoteService      = $deleteVoteService;
        $this->incrementVoteService   = $incrementVoteService;
        $this->decrementVoteService   = $decrementVoteService;
    }

    /**
     * Create new vote.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws VoteException
     * @throws AuthenticationException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'entity_id'   => 'required|numeric',
            'vote_entity' => 'required|numeric',
            'vote_type'   => 'required|numeric'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->createVoteService
            ->execute(
                $user->getId(),
                $parsedData
            );

        $result = $this->incrementVoteService
            ->execute(
                $parsedData['entity_id'],
                $parsedData['vote_entity'],
                $parsedData['vote_type'],
            );

        if (!$result) {
            $this->deleteVoteService->execute($user->getId(), $parsedData);
            throw new VoteException(__('The entity could not be incremented.'), 500);
        }

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * Returns total count of likes/dislikes for given entity.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     */
    public function getTotalVotes(Request $request, Response $response)
    {
        /** @var User $user */
        $user = user($request);

        $votes = $this->voteRepository->getUserVoteList($user->getId());

        return $this->respond(
            $response,
            response()
                ->setData($votes)
        );
    }

    /**
     * Delete vote.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws VoteException
     */
    public function delete(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'entity_id'   => 'required|numeric',
            'vote_entity' => 'required|numeric',
            'vote_type'   => 'required|numeric'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->deleteVoteService->execute($user->getId(), $parsedData);

        if (!$customResponse->getData()) {
            throw new VoteException(__('Vote could not be deleted.'), 409);
        }

        $result = $this->decrementVoteService
            ->execute(
                $parsedData['entity_id'],
                $parsedData['vote_entity'],
                $parsedData['vote_type'],
            );

        if (!$result) {
            $this->createVoteService->execute($user->getId(), $parsedData);
            throw new VoteException(__('The entity could not be incremented.'), 500);
        }

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
