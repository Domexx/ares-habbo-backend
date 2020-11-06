<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Service\Comment;

use Ares\Forum\Entity\Comment;
use Ares\Forum\Exception\CommentException;
use Ares\Forum\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class EditCommentService
 *
 * @package Ares\Forum\Service\Comment
 */
class EditCommentService
{
    /**
     * @var CommentRepository
     */
    private CommentRepository $commentRepository;

    /**
     * EditCommentService constructor.
     *
     * @param   CommentRepository  $commentRepository
     */
    public function __construct(
        CommentRepository $commentRepository
    ) {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws CommentException
     * @throws DataObjectManagerException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $comment_id */
        $comment_id = $data['comment_id'];

        /** @var string $content */
        $content = $data['content'];

        /** @var Comment $comment */
        $comment = $this->commentRepository->get((int) $comment_id);

        if (!$comment) {
            throw new CommentException(__('Comment not found'));
        }

        $comment
            ->setContent($content)
            ->setIsEdited(1);

        /** @var Comment $comment */
        $comment = $this->commentRepository->save($comment);

        return response()
            ->setData($comment);
    }
}
