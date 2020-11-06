<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Service\Comment;

use Ares\Forum\Entity\Comment;
use Ares\Forum\Entity\Thread;
use Ares\Forum\Exception\CommentException;
use Ares\Forum\Repository\CommentRepository;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class CreateCommentService
 *
 * @package Ares\Forum\Service\Comment
 */
class CreateCommentService
{
    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * @var ThreadRepository
     */
    private ThreadRepository $threadRepository;

    /**
     * CreateCommentService constructor.
     *
     * @param   CommentRepository  $commentRepository
     * @param   ThreadRepository   $threadRepository
     */
    public function __construct(
        CommentRepository $commentRepository,
        ThreadRepository $threadRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->threadRepository = $threadRepository;
    }

    /**
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

        return response()->setData($comment);
    }

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return Comment
     * @throws CommentException
     */
    public function getNewComment(int $userId, array $data): Comment
    {
        $comment = new Comment();

        /** @var Thread $thread */
        $thread = $this->threadRepository->get($data['thread_id']);

        if (!$thread) {
            throw new CommentException(__('Related Thread was not found.'), 404);
        }

        return $comment
            ->setThreadId($thread->getId())
            ->setUserId($userId)
            ->setContent($data['content'])
            ->setIsEdited(0)
            ->setLikes(0)
            ->setDislikes(0);
    }
}
