<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Thread;

use Ares\Forum\Entity\Thread;
use Ares\Forum\Entity\Topic;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Cocur\Slugify\Slugify;

/**
 * Class EditThreadService
 *
 * @package Ares\Forum\Service\Thread
 */
class EditThreadService
{
    /**
     * EditThreadService constructor.
     *
     * @param ThreadRepository $threadRepository
     * @param Slugify          $slug
     */
    public function __construct(
        private ThreadRepository $threadRepository,
        private Slugify $slug
    ) {}

    /**
     * @param Topic $topic
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(Topic $topic, int $userId, array $data): CustomResponseInterface
    {
        $thread = $this->threadRepository
            ->getExistingThread(
                $userId,
                $topic->getId(),
                $data['slug']
            );

        $thread
            ->setTitle($data['title'] ?: $thread->getTitle())
            ->setSlug($this->slug->slugify($data['title']))
            ->setDescription($data['description'] ?: $thread->getDescription())
            ->setContent($data['content'] ?: $thread->getContent())
            ->setUpdatedAt(new \DateTime());

        /** @var Thread $thread */
        $thread = $this->threadRepository->save($thread);

        return response()
            ->setData($thread);
    }
}
