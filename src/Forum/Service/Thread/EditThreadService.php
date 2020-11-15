<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Thread;

use Ares\Forum\Entity\Thread;
use Ares\Forum\Entity\Topic;
use Ares\Forum\Exception\ThreadException;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\User\Entity\User;

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
     * @param   ThreadRepository  $threadRepository
     */
    public function __construct(
        private ThreadRepository $threadRepository
    ) {}

    /**
     * @param Topic $topic
     * @param User  $user
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws ThreadException
     * @throws DataObjectManagerException
     */
    public function execute(Topic $topic, User $user, array $data): CustomResponseInterface
    {
        /** @var string $title */
        $slug = $data['slug'];

        /** @var string $title */
        $title = $data['title'];

        /** @var string $description */
        $description = $data['description'];

        /** @var string $content */
        $content = $data['content'];

        /**
         * @TODO ueufe
         */
        $searchCriteria = $this->threadRepository
            ->getDataObjectManager()
            ->where([
                'user_id' => $user->getId(),
                'topic_id' => $topic->getId(),
                'slug' => $slug
            ]);

        /** @var Thread $thread */
        $thread = $this->threadRepository
            ->getList($searchCriteria)
            ->first();

        if (!$thread) {
            throw new ThreadException(__('No Thread was found'));
        }

        $thread
            ->setTitle($title)
            ->setDescription($description)
            ->setContent($content)
            ->setUpdatedAt(new \DateTime());

        /** @var Thread $thread */
        $thread = $this->threadRepository->save($thread);

        return response()
            ->setData($thread);
    }
}
