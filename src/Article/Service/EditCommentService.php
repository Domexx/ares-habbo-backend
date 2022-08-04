<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Article\Service;

use Ares\Article\Entity\Comment;
use Ares\Article\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class EditCommentService
 *
 * @package Ares\Article\Service
 */
class EditCommentService
{
    /**
     * EditCommentService constructor.
     *
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        private CommentRepository $commentRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(array $data, int $userId): CustomResponseInterface
    {
        /** @var User $user */
        $user = $this->userRepository->get($userId);

        /** @var int $commentId */
        $commentId = $data['comment_id'];

        /** @var string $content */
        $content = $data['content'];

        /** @var Comment $comment */
        $comment = $this->commentRepository->get($commentId);

        if($comment->getUserId() == $userId || $user->hasPermission('manage-comments')) {
            $comment
            ->setContent($content)
            ->setIsEdited(1)
            ->setUpdatedAt(new \DateTime());

            /** @var Comment $comment */
            $comment = $this->commentRepository->save($comment);
        }

        return response()
            ->setData($comment);
    }
}
