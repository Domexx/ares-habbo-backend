<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Article;
use Ares\Article\Entity\Comment;
use Ares\Article\Exception\CommentException;
use Ares\Article\Repository\ArticleRepository;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class CreateCommentService
 *
 * @package Ares\Article\Service
 */
class CreateCommentService
{
    /**
     * @var ArticleRepository
     */
    private ArticleRepository $articleRepository;

    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * CreateCommentService constructor.
     *
     * @param ArticleRepository $articleRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Creates new comment.
     *
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws CommentException
     * @throws DataObjectManagerException
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
     * @throws CommentException
     */
    private function getNewComment(int $userId, array $data): Comment
    {
        $comment = new Comment();

        /** @var Article $article */
        $article = $this->articleRepository->get($data['article_id']);

        if (!$article) {
            throw new CommentException(__('Related article was not found.'), 404);
        }

        return $comment
            ->setContent($data['content'])
            ->setIsEdited(0)
            ->setUserId($userId)
            ->setLikes(0)
            ->setDislikes(0)
            ->setArticleId($article->getId())
            ->setLikes(0)
            ->setDislikes(0);
    }
}
