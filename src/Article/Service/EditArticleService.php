<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Article;
use Ares\Article\Repository\ArticleRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use DateTime;

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
     */
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $articleId */
        $articleId = $data['article_id'];

        /** @var Article $article */
        $article = $this->articleRepository->get($articleId);

        $article
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setContent($data['content'])
            ->setImage($data['image'])
            ->setHidden($data['hidden'])
            ->setPinned($data['pinned'])
            ->setUpdatedAt(new DateTime());

        /** @var Article $article */
        $article = $this->articleRepository->save($article);

        return response()
            ->setData($article);
    }
}
