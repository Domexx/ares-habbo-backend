<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Vote\Service;

use Ares\Article\Repository\ArticleRepository;
use Ares\Article\Repository\CommentRepository;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Repository\BaseRepository;
use Ares\Forum\Repository\CommentRepository as ThreadCommentRepository;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\Photo\Repository\PhotoRepository;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Interfaces\VoteEntityInterface;

/**
 * Class GetVoteEntityService
 *
 * @package Ares\Vote\Service
 */
class GetVoteEntityService
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
     * @var ThreadRepository
     */
    private ThreadRepository $threadRepository;

    /**
     * @var ThreadCommentRepository
     */
    private ThreadCommentRepository $threadCommentRepository;

    /**
     * @var PhotoRepository
     */
    private PhotoRepository $photoRepository;

    /**
     * @var GuestbookRepository
     */
    private GuestbookRepository $guestbookRepository;

    /**
     * GetVoteEntityService constructor.
     *
     * @param ArticleRepository       $articleRepository
     * @param CommentRepository       $commentRepository
     * @param ThreadRepository        $threadRepository
     * @param ThreadCommentRepository $threadCommentRepository
     * @param GuestbookRepository     $guestbookRepository
     * @param PhotoRepository         $photoRepository
     */
    public function __construct(
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        ThreadRepository $threadRepository,
        ThreadCommentRepository $threadCommentRepository,
        GuestbookRepository $guestbookRepository,
        PhotoRepository $photoRepository
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
        $this->threadRepository = $threadRepository;
        $this->threadCommentRepository = $threadCommentRepository;
        $this->guestbookRepository = $guestbookRepository;
        $this->photoRepository = $photoRepository;
    }

    /**
     * Takes id and entity and loads specific entity.
     *
     * @param int $entity
     *
     * @return BaseRepository|null
     * @throws VoteException
     */
    public function execute(int $entity): BaseRepository
    {
        switch (true) {
            case $entity === VoteEntityInterface::ARTICLE_VOTE_ENTITY:
                return $this->articleRepository;
            case $entity === VoteEntityInterface::ARTICLE_COMMENT_VOTE_ENTITY:
                return $this->commentRepository;
            case $entity === VoteEntityInterface::FORUM_VOTE_ENTITY:
                return $this->threadRepository;
            case $entity === VoteEntityInterface::FORUM_COMMENT_VOTE_ENTITY:
                return $this->threadCommentRepository;
            case $entity === VoteEntityInterface::GUESTBOOK_VOTE_ENTITY:
               return $this->guestbookRepository;
            case $entity === VoteEntityInterface::PHOTO_VOTE_ENTITY:
                return $this->photoRepository;
            default:
                throw new VoteException(__('Entity with id %s does not exist.', [$entity]), 404);
        }
    }
}
