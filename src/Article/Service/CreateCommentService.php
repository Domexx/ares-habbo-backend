<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Article;
use Ares\Article\Entity\Comment;
use Ares\Article\Repository\ArticleRepository;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use DateTime;

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
     */
    public function __construct(
        private ArticleRepository $articleRepository,
        private CommentRepository $commentRepository
    ) {}

    /**
     * Creates new comment.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException|NoSuchEntityException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $comment = $this->getNewComment($userId, $data);

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
     * @param array $data
     *
     * @return Comment
     * @throws NoSuchEntityException
     */
    private function getNewComment(int $userId, array $data): Comment
    {
        $comment = new Comment();

        /** @var Article $article */
        $article = $this->articleRepository->get($data['article_id']);

        return $comment
            ->setContent($data['content'])
            ->setIsEdited(0)
            ->setUserId($userId)
            ->setLikes(0)
            ->setDislikes(0)
            ->setArticleId($article->getId())
            ->setLikes(0)
            ->setDislikes(0)
            ->setCreatedAt(new DateTime());
    }
}
