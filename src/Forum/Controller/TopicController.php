<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Forum\Controller;

use Ares\Forum\Entity\Contract\TopicInterface;
use Ares\Forum\Entity\Topic;
use Ares\Forum\Exception\TopicException;
use Ares\Forum\Interfaces\Response\ForumResponseCodeInterface;
use Ares\Forum\Repository\TopicRepository;
use Ares\Forum\Service\Topic\CreateTopicService;
use Ares\Forum\Service\Topic\EditTopicService;
use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Service\ValidationService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class TopicController
 *
 * @package Ares\Forum\Controller
 */
class TopicController extends BaseController
{
    /**
     * TopicController constructor.
     *
     * @param   TopicRepository         $topicRepository
     * @param   CreateTopicService      $createTopicService
     * @param   EditTopicService        $editTopicService
     * @param   ValidationService       $validationService
     */
    public function __construct(
        private TopicRepository $topicRepository,
        private CreateTopicService $createTopicService,
        private EditTopicService $editTopicService,
        private ValidationService $validationService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws TopicException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            TopicInterface::COLUMN_TITLE => 'required|regex:/^[a-zA-Z0-9]+$/',
            TopicInterface::COLUMN_DESCRIPTION => 'required',
        ]);

        $customResponse = $this->createTopicService->execute($parsedData);

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws ValidationException
     */
    public function edit(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'topic_id' => 'required|numeric',
            'title'    => 'required',
            'content'  => 'required'
        ]);

        /** @var Topic $topic */
        $topic = $this->editTopicService->execute($parsedData);

        return $this->respond(
            $response,
            response()
                ->setData($topic)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function list(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $topics = $this->topicRepository
            ->getPaginatedTopicList(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($topics)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws TopicException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->topicRepository->delete($id);

        if (!$deleted) {
            throw new TopicException(
                __('Topic could not be deleted'),
                ForumResponseCodeInterface::RESPONSE_FORUM_TOPIC_NOT_DELETED,
                HttpResponseCodeInterface::HTTP_RESPONSE_UNPROCESSABLE_ENTITY
            );
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
