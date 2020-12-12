<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Controller;

use Ares\Article\Entity\Contract\CommentInterface;
use Ares\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Ares\Article\Repository\CommentRepository;
use Ares\Article\Service\CreateCommentService;
use Ares\Article\Service\EditCommentService;
use Ares\Article\Exception\CommentException;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class CommentController
 *
 * @package Ares\Article\Controller
 */
class CommentController extends BaseController
{
    /**
     * CommentController constructor.
     *
     * @param CommentRepository    $commentRepository
     * @param ValidationService    $validationService
     * @param CreateCommentService $createCommentService
     * @param EditCommentService   $editCommentService
     */
    public function __construct(
        private CommentRepository $commentRepository,
        private ValidationService $validationService,
        private CreateCommentService $createCommentService,
        private EditCommentService $editCommentService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws NoSuchEntityException|CommentException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            CommentInterface::COLUMN_CONTENT => 'required',
            CommentInterface::COLUMN_ARTICLE_ID => 'required|numeric'
        ]);

        /** @var User $user */
        $userId = user($request)->getId();

        $customResponse = $this->createCommentService->execute($userId, $parsedData);

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
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws ValidationException
     */
    public function edit(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            CommentInterface::COLUMN_ID => 'required|numeric',
            CommentInterface::COLUMN_CONTENT => 'required'
        ]);

        $customResponse = $this->editCommentService->execute($parsedData);

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
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $articleId */
        $articleId = $args['article_id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedCollection $comments */
        $comments = $this->commentRepository
            ->getPaginatedCommentList(
                $articleId,
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
            throw new CommentException(
                __('Comment could not be deleted'),
                ArticleResponseCodeInterface::RESPONSE_ARTICLE_COMMENT_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
