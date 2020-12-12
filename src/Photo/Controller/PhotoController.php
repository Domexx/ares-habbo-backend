<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Photo\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Service\ValidationService;
use Ares\Photo\Entity\Photo;
use Ares\Photo\Exception\PhotoException;
use Ares\Photo\Interfaces\Response\PhotoResponseCodeInterface;
use Ares\Photo\Repository\PhotoRepository;
use Ares\User\Entity\Contract\UserInterface;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class PhotoController
 *
 * @package Ares\Photo\Controller
 */
class PhotoController extends BaseController
{
    /**
     * PhotoController constructor.
     *
     * @param   PhotoRepository         $photoRepository
     * @param   UserRepository          $userRepository
     * @param   ValidationService       $validationService
     */
    public function __construct(
        private PhotoRepository $photoRepository,
        private UserRepository $userRepository,
        private ValidationService $validationService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     */
    public function photo(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Photo $photo */
        $photo = $this->photoRepository->get($id);
        $photo->getUser();

        return $this->respond(
            $response,
            response()
                ->setData($photo)
        );
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws NoSuchEntityException
     * @throws ValidationException
     */
    public function search(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            UserInterface::COLUMN_USERNAME => 'required'
        ]);

        /** @var string $username */
        $username = $parsedData['username'];

        /** @var User $user */
        $user = $this->userRepository->get($username, 'username');

        /** @var Photo $photo */
        $photo = $this->photoRepository->get($user->getId(), 'user_id');

        return $this->respond(
            $response,
            response()
                ->setData($photo)
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

        $photos = $this->photoRepository
            ->getPaginatedPhotoList(
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($photos)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws PhotoException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $photo = $this->photoRepository->delete($id);

        if (!$photo) {
            throw new PhotoException(
                __('Photo could not be deleted'),
                PhotoResponseCodeInterface::RESPONSE_PHOTO_NOT_DELETED,
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
