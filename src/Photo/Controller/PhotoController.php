<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Photo\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Photo\Entity\Photo;
use Ares\Photo\Exception\PhotoException;
use Ares\Photo\Repository\PhotoRepository;
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
     * @var PhotoRepository
     */
    private PhotoRepository $photoRepository;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * PhotoController constructor.
     *
     * @param   PhotoRepository         $photoRepository
     * @param   UserRepository          $userRepository
     * @param   ValidationService       $validationService
     */
    public function __construct(
        PhotoRepository $photoRepository,
        UserRepository $userRepository,
        ValidationService $validationService
    ) {
        $this->photoRepository   = $photoRepository;
        $this->validationService = $validationService;
        $this->userRepository    = $userRepository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws PhotoException
     */
    public function photo(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        /** @var Photo $photo */
        $photo = $this->photoRepository->get((int) $id);

        if (!$photo) {
            throw new PhotoException(__('No Photo was found'), 404);
        }
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
     * @throws PhotoException
     * @throws ValidationException
     */
    public function search(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'username' => 'required'
        ]);

        /** @var string $username */
        $username = $parsedData['username'];

        /** @var User $user */
        $user = $this->userRepository->get((string) $username, 'username');

        /** @var Photo $photo */
        $photo = $this->photoRepository->get($user->getId(), 'user_id');

        if (!$photo || !$user) {
            throw new PhotoException(__('No Photo was found'), 404);
        }

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
                (int) $page,
                (int) $resultPerPage
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

        $photo = $this->photoRepository->delete((int) $id);

        if (!$photo) {
            throw new PhotoException(__('Photo could not be deleted'));
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
