<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Forum\Service\Thread;

use Ares\Forum\Entity\Thread;
use Ares\Forum\Entity\Topic;
use Ares\Forum\Exception\ThreadException;
use Ares\Forum\Repository\ThreadRepository;
use Ares\Forum\Repository\TopicRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Cocur\Slugify\Slugify;

/**
 * Class CreateThreadService
 *
 * @package Ares\Forum\Service\Thread
 */
class CreateThreadService
{
    /**
     * @var ThreadRepository
     */
    private ThreadRepository $threadRepository;

    /**
     * @var TopicRepository
     */
    private TopicRepository $topicRepository;

    /**
     * @var Slugify
     */
    private Slugify $slug;

    /**
     * CreateThreadService constructor.
     *
     * @param ThreadRepository $threadRepository
     * @param TopicRepository  $topicRepository
     * @param Slugify          $slug
     */
    public function __construct(
        ThreadRepository $threadRepository,
        TopicRepository $topicRepository,
        Slugify $slug
    ) {
        $this->threadRepository = $threadRepository;
        $this->topicRepository = $topicRepository;
        $this->slug = $slug;
    }

    /**
     * @param int   $userId
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws ThreadException
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
     */
    public function getNewThread(int $userId, array $data): Thread
    {
        $thread = new Thread();

        /** @var Topic $topic */
        $topic = $this->topicRepository->get($data['topic_id']);

        /** @var Thread $existingThread */
        $existingThread = $this->threadRepository->get($data['title'], 'title');

        if (!$topic || $existingThread) {
            throw new ThreadException(__('There is already an existing Thread or the Topic could not be found'));
        }

        return $thread
            ->setUserId($userId)
            ->setTopicId($topic->getId())
            ->setSlug($this->slug->slugify($data['title']))
            ->setTitle($data['title'])
            ->setDescription($data['description'])
            ->setContent($data['content'])
            ->setLikes(0)
            ->setDislikes(0);
    }
}
