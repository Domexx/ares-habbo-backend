<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Controller;

use Ares\Article\Entity\Contract\ArticleInterface;
use Ares\Article\Service\CreateArticleService;
use Ares\Article\Service\DeleteArticleService;
use Ares\Article\Service\EditArticleService;
use Ares\Framework\Controller\BaseController;
use Ares\Article\Entity\Article;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class ArticleController
 *
 * @package Ares\Article\Controller
 */
class ArticleController extends BaseController
{
    /**
     * ArticleController constructor.
     *
     * @param ArticleRepository    $articleRepository
     * @param CreateArticleService $createArticleService
     * @param EditArticleService   $editArticleService
     * @param ValidationService    $validationService
     * @param DeleteArticleService $deleteArticleService
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private CreateArticleService $createArticleService,
        private EditArticleService $editArticleService,
        private ValidationService $validationService,
        private DeleteArticleService $deleteArticleService
    ) {}

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function getAllArticles(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedCollection $articles */
        $articles = $this->articleRepository->getPaginatedArticleList($page, $resultPerPage, true, true);

        return $this->respond($response, response()->setData($articles));
    }

    /**
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function getAvailableArticles(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedCollection $articles */
        $articles = $this->articleRepository->getPaginatedArticleList($page, $resultPerPage);

        return $this->respond($response, response()->setData($articles));
    }

    /**
     * Gets all Pinned Articles
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function getPinnedArticles(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var PaginatedCollection $pinnedArticles */
        $pinnedArticles = $this->articleRepository->getPaginatedPinnedArticles($page, $resultPerPage);

        return $this->respond($response, response()->setData($pinnedArticles));
    }

    /**
     * Searches with term in news.
     *
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function searchAnyArticles(Request $request, Response $response, array $args): Response
    {
        /** @var string $term */
        $term = $args['term'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $articles = $this->articleRepository->searchArticles($term, $page, $resultPerPage, true);

        return $this->respond($response, response()->setData($articles));
    }

    /**
     * Searches with term in news.
     *
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
    */
    public function searchArticles(Request $request, Response $response, array $args): Response
    {
        /** @var string $term */
        $term = $args['term'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $articles = $this->articleRepository->searchArticles($term, $page, $resultPerPage);

        return $this->respond($response, response()->setData($articles));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
    */
    public function getArticleBySlug(Request $request, Response $response, array $args): Response
    {
        /** @var string $slug */
        $slug = $args['slug'];

        /** @var Article $article */
        $article = $this->articleRepository->getArticleBySlugWithCommentCount($slug);

        return $this->respond(
            $response,
            response()
                ->setData($article)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
    */
    public function getArticleById(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Article $article */
        $article = $this->articleRepository->getArticleByIdWithCommentCount($id);

        return $this->respond($response, response()->setData($article));
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
    */
    public function getAnyArticleById(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Article $article */
        $article = $this->articleRepository->getArticleByIdWithCommentCount($id, true);

        return $this->respond($response, response()->setData($article));
    }

    /**
     * Creates new article.
     *
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws ArticleException
     * @throws DataObjectManagerException
     * @throws ValidationException
     * @throws AuthenticationException
     * @throws NoSuchEntityException
     */
    public function createArticle(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            ArticleInterface::COLUMN_TITLE => 'required',
            ArticleInterface::COLUMN_DESCRIPTION => 'required',
            ArticleInterface::COLUMN_CONTENT => 'required',
            ArticleInterface::COLUMN_IMAGE => 'required',
            ArticleInterface::COLUMN_THUMBNAIL => 'required',
            ArticleInterface::COLUMN_HIDDEN => 'required|numeric',
            ArticleInterface::COLUMN_PINNED => 'required|numeric'
        ]);

        /** @var User $user */
        $userId = user($request)->getId();

        $customResponse = $this->createArticleService->execute($userId, $parsedData);

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
     * @throws ValidationException|ArticleException
     */
    public function editArticle(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            ArticleInterface::COLUMN_ID => 'required|numeric',
            ArticleInterface::COLUMN_TITLE => 'required',
            ArticleInterface::COLUMN_DESCRIPTION => 'required',
            ArticleInterface::COLUMN_CONTENT => 'required',
            ArticleInterface::COLUMN_IMAGE => 'required',
            ArticleInterface::COLUMN_THUMBNAIL => 'required',
            ArticleInterface::COLUMN_HIDDEN => 'required|numeric',
            ArticleInterface::COLUMN_PINNED => 'required|numeric'
        ]);

        $customResponse = $this->editArticleService->execute($parsedData);

        return $this->respond($response, $customResponse);
    }

    /**
     * Deletes specific article.
     *
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws ArticleException
     * @throws DataObjectManagerException
     */
    public function deleteArticle(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $customResponse = $this->deleteArticleService->execute($id);

        return $this->respond($response, $customResponse);
    }
}
