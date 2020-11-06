<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Controller;

use Ares\Article\Service\CreateArticleService;
use Ares\Framework\Controller\BaseController;
use Ares\Article\Entity\Article;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\User\Entity\User;
use Illuminate\Pagination\LengthAwarePaginator;
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
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;

    /**
     * @var CreateArticleService
     */
    private CreateArticleService $createArticleService;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * NewsController constructor.
     *
     * @param   ArticleRepository       $articleRepository
     * @param   CreateArticleService    $createArticleService
     * @param   ValidationService       $validationService
     */
    public function __construct(
        ArticleRepository $articleRepository,
        CreateArticleService $createArticleService,
        ValidationService $validationService
    ) {
        $this->articleRepository    = $articleRepository;
        $this->createArticleService = $createArticleService;
        $this->validationService    = $validationService;
    }

    /**
     * Creates new article.
     *
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws ArticleException
     * @throws DataObjectManagerException
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
            'image'       => 'required',
            'hidden'      => 'required|numeric',
            'pinned'      => 'required|numeric'
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
     * @param Request     $request
     * @param Response    $response
     *
     * @param             $args
     *
     * @return Response
     * @throws ArticleException|DataObjectManagerException
     */
    public function article(Request $request, Response $response, array $args): Response
    {
        /** @var string $slug */
        $slug = $args['slug'];

        /** @var Article $article */
        $article = $this->articleRepository->get((string) $slug, 'slug');

        if (!$article) {
            throw new ArticleException(__('No specific Article found'), 404);
        }
        $article->getUser();

        return $this->respond(
            $response,
            response()
                ->setData($article)
        );
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
    public function pinned(Request $request, Response $response): Response
    {
        $pinnedArticles = $this->articleRepository->getPinnedArticles();

        return $this->respond(
            $response,
            response()
                ->setData($pinnedArticles)
        );
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
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var LengthAwarePaginator $articles */
        $articles = $this->articleRepository
            ->getPaginatedArticleList(
                (int) $page,
                (int) $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($articles)
        );
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
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->articleRepository->delete((int) $id);

        if (!$deleted) {
            throw new ArticleException(__('Article could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
