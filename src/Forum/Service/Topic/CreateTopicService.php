<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Topic;

use Ares\Forum\Entity\Topic;
use Ares\Forum\Exception\TopicException;
use Ares\Forum\Repository\TopicRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Cocur\Slugify\Slugify;
use DateTime;

/**
 * Class CreateTopicService
 *
 * @package Ares\Forum\Service\Topic
 */
class CreateTopicService
{
    /**
     * CreateTopicService constructor.
     *
     * @param TopicRepository $topicRepository
     * @param Slugify         $slug
     */
    public function __construct(
        private TopicRepository $topicRepository,
        private Slugify $slug
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws TopicException
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function execute(array $data): CustomResponseInterface
    {
        $topic = $this->getNewTopic($data);

        /** @var Topic $existingTopic */
        $existingTopic = $this->topicRepository->get($topic->getTitle(), 'title', true);

        if ($existingTopic) {
            throw new TopicException(__('Topic with the title %s already exists', [$existingTopic->getTitle()]));
        }

        /** @var Topic $topic */
        $topic = $this->topicRepository->save($topic);

        return response()
            ->setData($topic);
    }

    /**
     * @param   array  $data
     *
     * @return Topic
     */
    public function getNewTopic(array $data): Topic
    {
        $topic = new Topic();

        return $topic
            ->setTitle($data['title'])
            ->setSlug($this->slug->slugify($data['title']))
            ->setDescription($data['description'])
            ->setCreatedAt(new DateTime());
    }
}
