<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Article;
use Ares\Article\Entity\Contract\ArticleInterface;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Cocur\Slugify\Slugify;

/**
 * Class EditArticleService
 *
 * @package Ares\Article\Service
 */
class EditArticleService
{
    /**
     * EditArticleService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param Slugify           $slug
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private Slugify $slug
    ) {}

    /**
     * //TODO Resolve cache saving 'comments' count when retrieving from other functions
     * 
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException|ArticleException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $articleId */
        $articleId = $data['id'];

        /** @var Article $article */
        $article = $this->articleRepository->get($articleId, ArticleInterface::COLUMN_ID, false, false);
        
        /** @var Article $existingArticle */
        $existingArticle = $this->articleRepository->getExistingArticle($data['title'], $this->slug->slugify($data['title']));

        if ($existingArticle && $existingArticle->getId() !== $article->getId()) {

            throw new ArticleException(
                __('Article with given Title already exists'),
                ArticleResponseCodeInterface::RESPONSE_ARTICLE_TITLE_EXIST,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $article = $this->getEditedArticle($article, $data);

        /** @var Article $article */
        $article = $this->articleRepository->save($article);

        return response()
            ->setData($article);
    }

    /**
     * @param Article $article
     * @param array   $data
     *
     * @return Article
     */
    private function getEditedArticle(Article $article, array $data): Article
    {
        return $article
            ->setTitle($data['title'] ?: $article->getTitle())
            ->setSlug($this->slug->slugify($data['title']))
            ->setDescription($data['description'] ?: $article->getDescription())
            ->setContent($data['content'] ?: $article->getContent())
            ->setImage($data['image'] ?: $article->getImage())
            ->setThumbnail($data['thumbnail'] ?: $article->getThumbnail())
            ->setHidden($data['hidden']) //0 ?: 1 will always be 1
            ->setPinned($data['pinned'])
            ->setUpdatedAt(new \DateTime());
    }
}
