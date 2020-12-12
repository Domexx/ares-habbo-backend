<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Comment;

use Ares\Forum\Entity\Comment;
use Ares\Forum\Repository\CommentRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;

/**
 * Class EditCommentService
 *
 * @package Ares\Forum\Service\Comment
 */
class EditCommentService
{
    /**
     * EditCommentService constructor.
     *
     * @param   CommentRepository  $commentRepository
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
    public function execute(array $data): CustomResponseInterface
    {
        /** @var int $comment_id */
        $comment_id = $data['comment_id'];

        /** @var string $content */
        $content = $data['content'];

        /** @var Comment $comment */
        $comment = $this->commentRepository->get($comment_id);

        $comment
            ->setContent($content)
            ->setIsEdited(1)
            ->setUpdatedAt(new \DateTime());

        /** @var Comment $comment */
        $comment = $this->commentRepository->save($comment);

        return response()
            ->setData($comment);
    }
}
