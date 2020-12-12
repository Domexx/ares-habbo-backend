<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Comment;

use Ares\Forum\Entity\Comment;
use Ares\Forum\Entity\Thread;
use Ares\Forum\Repository\CommentRepository;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class CreateCommentService
 *
 * @package Ares\Forum\Service\Comment
 */
class CreateCommentService
{
    /**
     * CreateCommentService constructor.
     *
     * @param   CommentRepository  $commentRepository
     * @param   ThreadRepository   $threadRepository
     */
    public function __construct(
        private CommentRepository $commentRepository,
        private ThreadRepository $threadRepository
    ) {}

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
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
     * @param int   $userId
     * @param array $data
     *
     * @return Comment
     * @throws NoSuchEntityException
     */
    public function getNewComment(int $userId, array $data): Comment
    {
        $comment = new Comment();

        /** @var Thread $thread */
        $thread = $this->threadRepository->get($data['thread_id']);

        return $comment
            ->setThreadId($thread->getId())
            ->setUserId($userId)
            ->setContent($data['content'])
            ->setIsEdited(0)
            ->setLikes(0)
            ->setDislikes(0)
            ->setCreatedAt(new \DateTime());
    }
}
