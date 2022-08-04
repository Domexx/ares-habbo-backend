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
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Framework\Service\ValidationService;
use Ares\Guestbook\Entity\Contract\GuestbookInterface;
use Ares\Guestbook\Exception\GuestbookException;
use Ares\Guestbook\Interfaces\Response\GuestbookResponseCodeInterface;
use Ares\Guestbook\Repository\GuestbookRepository;
use Ares\Guestbook\Service\CreateGuestbookEntryService;
use Ares\Guestbook\Service\DeleteGuestbookEntryService;
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
     * @param DeleteGuestbookEntryService $deleteGuestbookEntryService
     */
    public function __construct(
        private GuestbookRepository $guestbookRepository,
        private UserRepository $userRepository,
        private GuildRepository $guildRepository,
        private ValidationService $validationService,
        private CreateGuestbookEntryService $createGuestbookEntryService,
        private DeleteGuestbookEntryService $deleteGuestbookEntryService
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
            GuestbookInterface::COLUMN_CONTENT => 'required',
            GuestbookInterface::COLUMN_PROFILE_ID => 'numeric',
            GuestbookInterface::COLUMN_GUILD_ID => 'numeric'
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
            throw new GuestbookException(
                __('The associated Entities could not be found'),
                GuestbookResponseCodeInterface::RESPONSE_GUESTBOOK_ASSOCIATED_ENTITIES_NOT_FOUND,
                HttpResponseCodeInterface::HTTP_RESPONSE_NOT_FOUND
            );
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
    public function getProfileGuestbookEntries(Request $request, Response $response, array $args): Response
    {
        /** @var int $profileId */
        $profileId = $args['id'];

        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        $entries = $this->guestbookRepository->getPaginatedProfileEntries($profileId, $page, $resultPerPage);

        return $this->respond($response, response()->setData($entries));
    }

    /**
     * @param Request     $request
     * @param Response    $response
     * @param             $args
     *
     * @return Response
     * @throws DataObjectManagerException
     */
    public function getGuildGuestbookEntries(Request $request, Response $response, array $args): Response
    {
        /** @var int $page */
        $page = $args['page'];

        /** @var int $resultPerPage */
        $resultPerPage = $args['rpp'];

        /** @var int $guildId */
        $guildId = $args['id'];

        $entries = $this->guestbookRepository->getPaginatedGuildEntries($guildId, $page, $resultPerPage);

        return $this->respond($response,response()->setData($entries));
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

        $customResponse = $this->deleteGuestbookEntryService->execute($id);

        return $this->respond(
            $response,
            $customResponse
        );
    }
}
