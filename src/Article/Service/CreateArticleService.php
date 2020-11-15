<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Article;
use Ares\Article\Exception\ArticleException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Cocur\Slugify\Slugify;
use DateTime;

/**
 * Class CreateArticleService
 *
 * @package Ares\Article\Service
 */
class CreateArticleService
{
    /**
     * CreateArticleService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param Slugify           $slug
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private Slugify $slug
    ) {}

    /**
     * Creates new article with given data.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws ArticleException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $article = $this->getNewArticle($userId, $data);

        /** @var Article $existingArticle */
        $existingArticle = $this->articleRepository->get($article->getTitle(), 'title', true);

        if ($existingArticle) {
            throw new ArticleException(__('Article with given Title already exists.'), 409);
        }

        /** @var Article $article */
        $article = $this->articleRepository->save($article);

        return response()
            ->setData($article);
    }

    /**
     * Returns new article.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return Article
     */
    private function getNewArticle(int $userId, array $data): Article
    {
        $article = new Article();

        return $article
            ->setTitle($data['title'])
            ->setSlug($this->slug->slugify($data['title']))
            ->setDescription($data['description'])
            ->setContent($data['content'])
            ->setImage($data['image'])
            ->setAuthorId($userId)
            ->setHidden($data['hidden'])
            ->setPinned($data['pinned'])
            ->setLikes(0)
            ->setDislikes(0)
            ->setCreatedAt(new DateTime());
    }
}
