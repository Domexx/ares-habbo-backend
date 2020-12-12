<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Article;
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
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException|ArticleException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $articleId */
        $articleId = $data['article_id'];

        /** @var Article $article */
        $article = $this->articleRepository->get($articleId);

        if ($article->getTitle() === $data['title']) {
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
            ->setHidden($data['hidden'] ?: $article->getHidden())
            ->setPinned($data['pinned'] ?: $article->getPinned())
            ->setUpdatedAt(new \DateTime());
    }
}
