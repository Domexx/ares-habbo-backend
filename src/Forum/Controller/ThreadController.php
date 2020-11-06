<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Controller;

use Ares\Forum\Entity\Thread;
use Ares\Forum\Exception\ThreadException;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Forum\Service\Thread\CreateThreadService;
use Ares\Forum\Service\Thread\EditThreadService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ThreadController extends BaseController
{
    /**
     * @var ThreadRepository
     */
    private ThreadRepository $threadRepository;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var CreateThreadService
     */
    private CreateThreadService $createThreadService;

    /**
     * @var EditThreadService
     */
    private EditThreadService $editThreadService;

    /**
     * CommentController constructor.
     *
     * @param   ThreadRepository        $threadRepository
     * @param   CreateThreadService     $createThreadService
     * @param   EditThreadService       $editThreadService
     * @param   ValidationService       $validationService
     */
    public function __construct(
        ThreadRepository $threadRepository,
        CreateThreadService $createThreadService,
        EditThreadService $editThreadService,
        ValidationService $validationService
    ) {
        $this->threadRepository    = $threadRepository;
        $this->createThreadService = $createThreadService;
        $this->editThreadService   = $editThreadService;
        $this->validationService   = $validationService;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws ThreadException
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'title'       => 'required',
            'description' => 'required',
            'content'     => 'required',
            'topic_id'    => 'required|numeric'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->createThreadService->execute($user->getId(), $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ThreadException|DataObjectManagerException
     */
    public function thread(Request $request, Response $response, array $args): Response
    {
        /** @var string $slug */
        $slug = $args['slug'];

        /** @var int $topicId */
        $topicId = $args['topic_id'];

        /** @var Thread $thread */
        $thread = $this->threadRepository
            ->getSingleThread(
                (int) $topicId,
                (string) $slug
            );

        if (!$thread) {
            throw new ThreadException(__('No specific Thread found'), 404);
        }

        return $this->respond(
            $response,
            response()
                ->setData($thread)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var int $topicId */
        $topicId = $args['topic_id'];

        $threads = $this->threadRepository
            ->getPaginatedThreadList(
                (int) $topicId,
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($threads)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ThreadException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->threadRepository->delete((int) $id);

        if (!$deleted) {
            throw new ThreadException(__('Thread could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
