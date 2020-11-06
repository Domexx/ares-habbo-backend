<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guestbook\Controller;

use Ares\Framework\Controller\BaseController;
use Ares\Framework\Exception\AuthenticationException;
use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\ValidationException;
use Ares\Framework\Service\ValidationService;
use Ares\Guestbook\Exception\GuestbookException;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\Guestbook\Service\CreateGuestbookEntryService;
use Ares\Guild\Entity\Guild;
use Ares\Guild\Repository\GuildRepository;
use Ares\User\Entity\User;
use Ares\User\Exception\UserException;
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
     * @var GuestbookRepository
     */
    private GuestbookRepository $guestbookRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var ValidationService
     */
    private ValidationService $validationService;

    /**
     * @var CreateGuestbookEntryService
     */
    private CreateGuestbookEntryService $createGuestbookEntryService;

    /**
     * @var GuildRepository
     */
    private GuildRepository $guildRepository;

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
        GuestbookRepository $guestbookRepository,
        UserRepository $userRepository,
        GuildRepository $guildRepository,
        ValidationService $validationService,
        CreateGuestbookEntryService $createGuestbookEntryService
    ) {
        $this->guestbookRepository = $guestbookRepository;
        $this->userRepository = $userRepository;
        $this->guildRepository = $guildRepository;
        $this->validationService = $validationService;
        $this->createGuestbookEntryService = $createGuestbookEntryService;
    }

    /**
     * @param Request $request
     * @param Response $response
     *
     * @return Response
     * @throws DataObjectManagerException
     * @throws GuestbookException
     * @throws ValidationException
     * @throws AuthenticationException
     */
    public function create(Request $request, Response $response): Response
    {
        /** @var array $parsedData */
        $parsedData = $request->getParsedBody();

        $this->validationService->validate($parsedData, [
            'content' => 'required',
            'profile_id' => 'numeric',
            'guild' => 'numeric'
        ]);

        /** @var int $profileId */
        $profileId = $parsedData['profile_id'] ?? 0;

        /** @var int $guildId */
        $guildId = $parsedData['guild_id'] ?? 0;

        /** @var User $user */
        $user = user($request);

        /** @var User $profile */
        $profile = $this->userRepository->get((int) $profileId);

        /** @var Guild $guild */
        $guild = $this->guildRepository->get((int) $guildId);

        if (!$profile && !$guild) {
            throw new GuestbookException(__('The associated Entities could not be found'));
        }

        $parsedData['profile_id'] = $profile->getId();
        $parsedData['guild_id'] = $guild->getId();

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
                (int) $profileId,
                (int) $page,
                (int) $resultPerPage
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
                (int) $guildId,
                (int) $page,
                (int) $resultPerPage
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

        $deleted = $this->guestbookRepository->delete((int) $id);

        if (!$deleted) {
            throw new GuestbookException(__('Guestbook Entry could not be deleted.'), 409);
        }

        return $this->respond(
            $response,
            response()
                ->setData(true)
        );
    }
}
