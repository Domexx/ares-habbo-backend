<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see       LICENSE (MIT)
 */

namespace Ares\Vote\Service;

use Ares\Article\Repository\ArticleRepository;
use Ares\Article\Repository\CommentRepository;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Repository\BaseRepository;
use Ares\Forum\Repository\CommentRepository as ThreadCommentRepository;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\Photo\Repository\PhotoRepository;
use Ares\Vote\Exception\VoteException;
use Ares\Vote\Interfaces\Response\VoteResponseCodeInterface;
use Ares\Vote\Interfaces\VoteEntityInterface;

/**
 * Class GetVoteEntityService
 *
 * @package Ares\Vote\Service
 */
class GetVoteEntityService
{
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
        private ArticleRepository $articleRepository,
        private CommentRepository $commentRepository,
        private ThreadRepository $threadRepository,
        private ThreadCommentRepository $threadCommentRepository,
        private GuestbookRepository $guestbookRepository,
        private PhotoRepository $photoRepository
    ) {}

    /**
     * Takes id and entity and loads specific entity.
     *
     * @param int $entity
     *
     * @return BaseRepository
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
                throw new VoteException(
                    __('Entity with id %s does not exist',
                        [$entity]),
                    VoteResponseCodeInterface::RESPONSE_VOTE_ENTITY_NOT_EXIST,
                    HttpResponseCodeInterface::HTTP_RESPONSE_NOT_FOUND
                );
        }
    }
}
