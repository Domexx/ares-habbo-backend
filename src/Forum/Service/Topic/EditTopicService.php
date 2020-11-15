<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Service\Topic;

use Ares\Forum\Entity\Topic;
use Ares\Forum\Repository\TopicRepository;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use DateTime;

/**
 * Class EditTopicService
 *
 * @package Ares\Forum\Service\Topic
 */
class EditTopicService
{
    /**
     * EditTopicService constructor.
     *
     * @param   TopicRepository  $topicRepository
     */
    public function __construct(
        private TopicRepository $topicRepository
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
        /** @var string $title */
        $title = $data['title'];

        /** @var int $topicId */
        $topicId = $data['topic_id'];

        /** @var string $description */
        $description = $data['description'];

        /** @var Topic $topic */
        $topic = $this->topicRepository->get($topicId);

        $topic
            ->setTitle($title)
            ->setDescription($description)
            ->setUpdatedAt(new DateTime());

        /** @var Topic $topic */
        $topic = $this->topicRepository->save($topic);

        return response()
            ->setData($topic);
    }
}
