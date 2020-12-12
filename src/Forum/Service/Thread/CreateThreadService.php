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
use Ares\Forum\Interfaces\Response\ForumResponseCodeInterface;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Forum\Repository\TopicRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Cocur\Slugify\Slugify;

/**
 * Class CreateThreadService
 *
 * @package Ares\Forum\Service\Thread
 */
class CreateThreadService
{
    /**
     * CreateThreadService constructor.
     *
     * @param ThreadRepository $threadRepository
     * @param TopicRepository  $topicRepository
     * @param Slugify          $slug
     */
    public function __construct(
        private ThreadRepository $threadRepository,
        private TopicRepository $topicRepository,
        private Slugify $slug
    ) {}

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws ThreadException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data): CustomResponseInterface
    {
        $topic = $this->getNewThread($userId, $data);

        /** @var Topic $topic */
        $topic = $this->threadRepository->save($topic);

        return response()
            ->setData($topic);
    }

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return Thread
     * @throws ThreadException
     * @throws NoSuchEntityException
     */
    public function getNewThread(int $userId, array $data): Thread
    {
        $thread = new Thread();

        /** @var Topic $topic */
        $topic = $this->topicRepository->get($data['topic_id'], 'id', true);

        /** @var Thread $existingThread */
        $existingThread = $this->threadRepository->get($data['title'], 'title', true);

        if (!$topic || $existingThread) {
            throw new ThreadException(
                __('There is already an existing Thread or the Topic could not be found'),
                ForumResponseCodeInterface::RESPONSE_FORUM_THREAD_THREAD_EXISTS_OR_NO_TOPIC,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return $thread
            ->setUserId($userId)
            ->setTopicId($topic->getId())
            ->setSlug($this->slug->slugify($data['title']))
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setContent($data['content'])
            ->setLikes(0)
            ->setDislikes(0)
            ->setCreatedAt(new \DateTime());
    }
}
