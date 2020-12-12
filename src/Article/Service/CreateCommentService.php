<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Article;
use Ares\Article\Entity\Comment;
use Ares\Article\Exception\CommentException;
use Ares\Article\Interfaces\Response\ArticleResponseCodeInterface;
use Ares\Article\Repository\ArticleRepository;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use PHLAK\Config\Config;

/**
 * Class CreateCommentService
 *
 * @package Ares\Article\Service
 */
class CreateCommentService
{
    /**
     * CreateCommentService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param CommentRepository $commentRepository
     * @param Config            $config
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private CommentRepository $commentRepository,
        private Config $config
    ) {}

    /**
     * Creates new comment.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException|NoSuchEntityException
     * @throws CommentException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        /** @var Article $article */
        $article = $this->articleRepository->get($data['article_id']);

        $commentCount = $this->commentRepository->getUserCommentCount($userId, $article->getId());

        if ($commentCount >= $this->config->get('hotel_settings.news.comment_max')) {
            throw new CommentException(
                __('User exceeded allowed comments'),
                ArticleResponseCodeInterface::RESPONSE_ARTICLE_COMMENT_EXCEEDED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        $comment = $this->getNewComment($userId, $article->getId(), $data);

        /** @var Comment $comment */
        $comment = $this->commentRepository->save($comment);
        $comment->getUser();

        return response()
            ->setData($comment);
    }

    /**
     * Returns new comment object with data.
     *
     * @param int   $userId
     * @param int   $articleId
     * @param array $data
     *
     * @return Comment
     */
    private function getNewComment(int $userId, int $articleId, array $data): Comment
    {
        $comment = new Comment();

        return $comment
            ->setContent($data['content'])
            ->setIsEdited(0)
            ->setUserId($userId)
            ->setLikes(0)
            ->setDislikes(0)
            ->setArticleId($articleId)
            ->setLikes(0)
            ->setDislikes(0)
            ->setCreatedAt(new \DateTime());
    }
}
