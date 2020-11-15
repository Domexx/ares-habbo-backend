<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Controller;

use Ares\Forum\Entity\Comment;
use Ares\Forum\Exception\CommentException;
use Ares\Forum\Repository\CommentRepository;
use Ares\Forum\Service\Comment\CreateCommentService;
use Ares\Forum\Service\Comment\EditCommentService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class CommentController
 *
 * @package Ares\Forum\Controller
 */
class CommentController extends BaseController
{
    /**
     * CommentController constructor.
     *
     * @param   CommentRepository       $commentRepository
     * @param   CreateCommentService    $createCommentService
     * @param   EditCommentService      $editCommentService
     * @param   ValidationService       $validationService
     */
    public function __construct(
        private CommentRepository $commentRepository,
        private CreateCommentService $createCommentService,
        private EditCommentService $editCommentService,
        private ValidationService $validationService
    ) {}

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws CommentException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'thread_id' => 'required|numeric',
            'content'   => 'required'
        ]);

        /** @var User $user */
        $user = user($request);

        $customResponse = $this->createCommentService->execute($user->getId(), $parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws CommentException
     * @throws DataObjectManagerException
     * @throws ValidationException
     */
    public function edit(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'thread_id' => 'required|numeric',
            'content'   => 'required'
        ]);

        /** @var Comment $comment */
        $comment = $this->editCommentService->execute($parsedData);

        return $this->respond(
            $response,
            response()
                ->setData($comment)
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

        /** @var int $threadId */
        $threadId = $args['thread_id'];

        /** @var LengthAwarePaginator $comments */
        $comments = $this->commentRepository
            ->getPaginatedThreadCommentList(
                $threadId,
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($comments)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws CommentException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->commentRepository->delete($id);

        if (!$deleted) {
            throw new CommentException(__('Comment could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
