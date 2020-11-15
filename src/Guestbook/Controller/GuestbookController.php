<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Guestbook\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Guestbook\Exception\GuestbookException;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\Guestbook\Service\CreateGuestbookEntryService;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Repository\GuildRepository;
use Ares\User\Entity\User;
use Ares\User\Repository\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Class GuestbookController
 *
 * @package Ares\Guestbook\Controller
 */
class GuestbookController extends BaseController
{
    /**
     * GuestbookController constructor.
     *
     * @param GuestbookRepository         $guestbookRepository
     * @param UserRepository              $userRepository
     * @param GuildRepository             $guildRepository
     * @param ValidationService           $validationService
     * @param CreateGuestbookEntryService $createGuestbookEntryService
     */
    public function __construct(
        private GuestbookRepository $guestbookRepository,
        private UserRepository $userRepository,
        private GuildRepository $guildRepository,
        private ValidationService $validationService,
        private CreateGuestbookEntryService $createGuestbookEntryService
    ) {}

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     * @throws AuthenticationException
     * @throws DataObjectManagerException
     * @throws GuestbookException
     * @throws ValidationException
     * @throws NoSuchEntityException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'content' => 'required',
            'profile_id' => 'numeric',
            'guild_id' => 'numeric'
        ]);

        /** @var int $profileId */
        $profileId = $parsedData['profile_id'] ?? null;

        /** @var int $guildId */
        $guildId = $parsedData['guild_id'] ?? null;

        /** @var User $user */
        $user = user($request);

        /** @var User $profile */
        $profile = $this->userRepository->get($profileId, 'id', true);

        /** @var Guild $guild */
        $guild = $this->guildRepository->get($guildId, 'id', true);

        if (!$profile && !$guild) {
            throw new GuestbookException(__('The associated Entities could not be found'));
        }

        $parsedData['profile_id'] = (!$profile) ? null : $profile->getId();
        $parsedData['guild_id'] = (!$guild) ? null : $guild->getId();

        $customResponse = $this->createGuestbookEntryService
            ->execute(
                $user->getId(),
                $parsedData
            );

        return $this->respond(
            $response,
            $customResponse
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function profileList(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var int $profileId */
        $profileId = $args['profile_id'];

        $entries = $this->guestbookRepository
            ->getPaginatedProfileEntries(
                $profileId,
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($entries)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function guildList(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var int $guildId */
        $guildId = $args['guild_id'];

        $entries = $this->guestbookRepository
            ->getPaginatedGuildEntries(
                $guildId,
                $page,
                $resultPerPage
            );

        return $this->respond(
            $response,
            response()
                ->setData($entries)
        );
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws GuestbookException
     * @throws DataObjectManagerException
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        /** @var int $id */
        $id = $args['id'];

        $deleted = $this->guestbookRepository->delete($id);

        if (!$deleted) {
            throw new GuestbookException(__('Guestbook entry could not be deleted'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
