<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Repository;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Repository\BaseRepository;
use Ares\Framework\Model\Query\Collection;
use Ares\Framework\Model\Query\PaginatedCollection;
use Ares\Article\Entity\Article;

/**
 * Class ArticleRepository
 *
 * @package Ares\Article\Repository
 */
class ArticleRepository extends BaseRepository
{
    /** @var string */
    protected string $cachePrefix = 'ARES_ARTICLE_';

    /** @var string */
    protected string $cacheCollectionPrefix = 'ARES_ARTICLE_COLLECTION_';

    /** @var string */
    protected string $entity = Article::class;

    /**
     * @param string $term
     * @param int    $page
     * @param int    $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function searchArticles(string $term, int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'ares_articles.id', 'ares_articles.author_id', 'ares_articles.title', 'ares_articles.slug',
                'ares_articles.description', 'ares_articles.image', 'ares_articles.likes', 'ares_articles.dislikes',
                'ares_articles.created_at'
            ])->selectRaw(
                'count(ares_articles_comments.article_id) as comments'
            )->leftJoin(
                'ares_articles_comments',
                'ares_articles.id',
                '=',
                'ares_articles_comments.article_id'
            )->where('title', 'LIKE', '%' . $term . '%')
            ->where('hidden', 0)
            ->groupBy('ares_articles.id')
            ->orderBy('comments', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @return Collection|null
     * @throws DataObjectManagerException
     */
    public function getPinnedArticles(): ?Collection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'ares_articles.id', 'ares_articles.author_id', 'ares_articles.title', 'ares_articles.slug',
                'ares_articles.description', 'ares_articles.image', 'ares_articles.likes', 'ares_articles.dislikes',
                'ares_articles.created_at'
            ])->selectRaw(
                'count(ares_articles_comments.article_id) as comments'
            )->leftJoin(
                'ares_articles_comments',
                'ares_articles.id',
                '=',
                'ares_articles_comments.article_id'
            )->where([
                'pinned' => 1,
                'hidden' => 0
            ])->groupBy('ares_articles.id')
            ->orderBy('ares_articles.id', 'DESC')
            ->limit(3)
            ->addRelation('user');

        return $this->getList($searchCriteria);
    }

    /**
     * @param int $page
     * @param int $resultPerPage
     *
     * @return PaginatedCollection
     * @throws DataObjectManagerException
     */
    public function getPaginatedArticleList(int $page, int $resultPerPage): PaginatedCollection
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'ares_articles.id', 'ares_articles.author_id', 'ares_articles.title', 'ares_articles.slug',
                'ares_articles.description', 'ares_articles.image', 'ares_articles.likes', 'ares_articles.dislikes',
                'ares_articles.created_at'
            ])->selectRaw(
                'count(ares_articles_comments.article_id) as comments'
            )->leftJoin(
                'ares_articles_comments',
                'ares_articles.id',
                '=',
                'ares_articles_comments.article_id'
            )->groupBy('ares_articles.id')
            ->where('ares_articles.hidden', 0)
            ->orderBy('ares_articles.id', 'DESC')
            ->addRelation('user');

        return $this->getPaginatedList($searchCriteria, $page, $resultPerPage);
    }

    /**
     * @param string $slug
     *
     * @return Article|null
     * @throws DataObjectManagerException|NoSuchEntityException
     */
    public function getArticleWithCommentCount(string $slug): ?Article
    {
        $searchCriteria = $this->getDataObjectManager()
            ->select([
                'ares_articles.id', 'ares_articles.author_id', 'ares_articles.content',
                'ares_articles.title', 'ares_articles.slug', 'ares_articles.description',
                'ares_articles.image', 'ares_articles.likes', 'ares_articles.dislikes', 'ares_articles.created_at'
            ])->selectRaw(
                'count(ares_articles_comments.article_id) as comments'
            )->leftJoin(
                'ares_articles_comments',
                'ares_articles.id',
                '=',
                'ares_articles_comments.article_id'
            )->groupBy('ares_articles.id')
            ->where('slug', $slug)
            ->addRelation('user');

        return $this->getOneBy($searchCriteria);
    }

    /**
     * @param string $title
     * @param string $slug
     *
     * @return Article|null
     * @throws NoSuchEntityException
     */
    public function getExistingArticle(string $title, string $slug): ?Article
    {
        $searchCriteria = $this->getDataObjectManager()
            ->where([
                'title' => $title,
                'slug' => $slug
            ]);

        return $this->getOneBy($searchCriteria, true);
    }
}
